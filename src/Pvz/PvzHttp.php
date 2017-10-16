<?php

namespace SpExt\Pvz;

use SpExt\ApiWrapper\Http\Request;
use SpExt\ApiWrapper\Http\ResponseMapper;
use SpExt\ApiWrapper\Model\AbstractModel;

/**
 * @internal Config $clientConfig
 */
trait PvzHttp
{
    /**
     * @param string              $methodName
     * @param array|AbstractModel $parameters
     * @param null                $mapperClass
     * @param null                $mapperField
     *
     * @return mixed
     */
    protected function apiRequest($methodName, $parameters = [], $mapperClass = null, $mapperField = null)
    {
        $responseMapper = $mapperClass ? new ResponseMapper($mapperClass, $mapperField) : null;

        $parameters = $this->processParameters($parameters);

        try {
            $result = $this->createRequest(
                new Request('POST', $methodName, $parameters),
                $responseMapper
            );
        } catch (\RuntimeException $e) {
            $errorData = @\json_decode($e->getMessage());

            if (is_array($errorData) && $errorData[0]->param ==='session_id') {
                $this->updateSession();

                $result = $this->createRequest(
                    new Request('POST', $methodName, $parameters),
                    $responseMapper
                );
            }

            throw new \RuntimeException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param array|AbstractModel $parameters
     *
     * @return array
     */
    protected function processParameters($parameters)
    {
        if ($parameters instanceof AbstractModel) {
            $parameters->validate();
            return $parameters->toArray();
        }

        return $parameters;
    }

    /**
     * Check if cache is disabled for certain methods
     *
     * @throws \InvalidArgumentException
     */
    protected function checkCacheDisabled()
    {
        if ($this->clientConfig->cacheEnabled) {
            throw new \InvalidArgumentException('For method "CreateParcel" you need to disable cache "new Pvz77($config, true)"');
        }
    }
}
