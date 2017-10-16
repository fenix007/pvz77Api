<?php

namespace SpExt\ApiWrapper\Model;

use SpExt\ApiWrapper\Http\IRequestObject;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractModel implements IRequestObject
{
    use Validator;

    /**
     * Prefix keys when array conversion
     * @var string
     */
    protected $prefix;

    /**
     * List of properties to populate by the ObjectHydrator
     *
     * @var array
     */
    public static $properties = [];

    /**
     * AbstractModel constructor.
     */
    public function __construct()
    {
        self::$properties = array_keys(get_object_vars($this));
    }

    public function toJson()
    {
        return \json_encode($this);
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function create(array $data = [])
    {
        $accessor = new PropertyAccessor();
        $object = new static();
        
        foreach ($data as $key => $value) {
            if (!$accessor->isWritable($object, $key)) {
                continue;
            }

            $accessor->setValue($object, $key, $value);
        }

        return $object;
    }

    public function toArray($prefix = null, $whithChild = true)
    {
        $result = get_object_vars($this);

        $accessor = new PropertyAccessor();

        $result = array_filter($result, function ($field) use ($accessor) {
            return $accessor->isWritable($this, $field);
        }, ARRAY_FILTER_USE_KEY);

        if (! $prefix && isset($this->prefix)) {
            $prefix = $this->prefix;
        }

        if ($prefix) {
            $result = array_combine(
                array_map(function ($field) use ($prefix) {
                    return $prefix . $field;
                }, array_keys($result)),
                $result
            );
        }

        if (! $whithChild) {
            return $result;
        }

        /**
         * Convert child Models
         */
        foreach ($result as $key => $element) {
            if ($element instanceof AbstractModel) {
                unset($result[$key]);

                $result = array_merge($element->toArray(), $result);
            }
        }

        return $result;
    }

    /**
     * Get array data with key prefix
     *
     * @param array $data
     * @param       $prefix
     *
     * @return array
     */
    protected static function getDataWithPrefix(array $data, $prefix)
    {
        $result = [];

        foreach ($data as $key => $row) {
            if (strpos($key, $prefix) === 0) {
                $result [substr($key, strlen($prefix))] = $row;
            }
        }

        return $result;
    }
}
