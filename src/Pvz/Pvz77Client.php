<?php

namespace SpExt\Pvz;

use SpExt\ApiWrapper\Http\HttpClient;
use SpExt\ApiWrapper\Http\Request;
use SpExt\ApiWrapper\Http\ResponseMapper;
use SpExt\ApiWrapper\Model\AbstractModel;

class Pvz77Client extends HttpClient
{
    use PvzSession;
    use PvzHttp;
    use PvzMiddleware;

    /**
     * @param string              $methodName
     * @param array|AbstractModel $parameters
     * @param null                $mapperClass
     * @param null                $mapperField
     *
     * @return mixed
     */
    public function apiRequest($methodName, $parameters = [], $mapperClass = null, $mapperField = null)
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
}
