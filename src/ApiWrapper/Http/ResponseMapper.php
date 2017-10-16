<?php

namespace SpExt\ApiWrapper\Http;

class ResponseMapper
{
    /** @var  string */
    protected $mapperClass;

    /** @var  string */
    protected $field;

    /**
     * ResponseMapper constructor.
     *
     * @param string $mapperClass
     * @param string $field
     */
    public function __construct($mapperClass, $field)
    {
        $this->mapperClass = $mapperClass;
        $this->field       = $field;
    }

    /**
     * @param Response|array $response
     *
     * @return array
     */
    public function map($response)
    {
        $data = (array)$response;
        $data = isset($data[$this->field]) ? $data[$this->field] : $data;

        if (!$this->mapperClass) {
            return $data;
        }

        $model = new $this->mapperClass;

        if (! is_array($data) || $this->isArrayAssoc($data)) {
            return $model::create($data);
        }

        return array_map(function ($raw) use ($model) {
            return $model::create($raw);
        }, $data);
    }

    /**
     * Check if array is assoc
     *
     * @param array $arr
     *
     * @return bool
     */
    protected function isArrayAssoc(array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
