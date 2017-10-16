<?php

namespace SpExt\ApiWrapper\Http;

use SpExt\ApiWrapper\Model\AbstractModel;

/**
 * Class Request
 */
class Request
{
    const METHODS = [
        'GET',
        'HEAD',
        'PUT',
        'POST',
        'PATCH',
        'DELETE'
    ];

    const TIMESTAMP_FIELD = 'timestamp';

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Request constructor.
     *
     * @param string $path
     * @param string $method
     * @param array  $parameters
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($method = 'GET', $path = '/', array $parameters = [])
    {
        if (!in_array($method, self::METHODS)) {
            throw new \InvalidArgumentException("Unsupproted method '$method'");
        }

        $this->path       = $path;
        $this->method     = $method;
        $this->parameters = $parameters;

        $this->replaceVariables();
    }

    /**
     * Replace $params in url like {id} to it's value
     */
    public function replaceVariables()
    {
        $path    = $this->getPath();

        foreach ($this->parameters as $key => $value) {
            if (strpos($path, '{' . $key . '}') !== false) {
                $path = self::strReplaceFirst('{' . $key . '}', $value, $path);
                unset($this->parameters[$key]);
            }
        }

        $this->setPath($path);
    }

    protected static function strReplaceFirst($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $subject, 1);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->checkModelInParams() ?
            [$this->toJson()] :
            $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public static function createFromMethod(array $method, array $options = [])
    {
        return new static(
            $method['method'],
            $method['path'],
            $options
        );
    }

    protected function checkModelInParams()
    {
        return count(array_filter($this->parameters, function ($el) {
            return $el instanceof AbstractModel;
        }));
    }

    public function toJson()
    {
        if (!count($this->parameters)) {
            return '';
        }

        $parameters = [];
        foreach ($this->parameters as $parameter) {
            $parameters []= $parameter instanceof AbstractModel ?
                $parameter->toJson() :
                $parameter;
        }

        return \json_encode($parameters);
    }

    public function addHeader($name, $value)
    {
        $headers = isset($this->parameters['headers']) ? $this->parameters['headers'] : [];

        $headers[$name] = $value;

        $this->parameters['header'] = $headers;
    }

    public function addTimestamp()
    {
        $this->addParameter(static::TIMESTAMP_FIELD, time());

        return $this;
    }

    public function addSignature($value, $field = 'signature')
    {
        $this->addParameter($field, $value);

        return $this;
    }
}
