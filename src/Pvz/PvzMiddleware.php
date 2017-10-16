<?php

namespace SpExt\Pvz;

use SpExt\ApiWrapper\Http\Response;

//TODO: move to guzzle middleware via config
trait PvzMiddleware
{
    protected function preProcessUrl($url)
    {
        return $this->clientConfig->url . $url . '/';
    }

    protected function preProcessParameters(array $parameters)
    {
        if ($this->withSessionId) {
            $parameters['session_id'] = $this->getSessionId();
        }

        /** format to json body response */
        return ['json' => $parameters];
    }

    /**
     * @param Response $response
     *
     * @throws \RuntimeException
     * @return array
     */
    protected function preProcessResponse($response)
    {
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Connection Error');
        }

        $content = $response->getContentOrNull();
        $data    = json_decode($content, true);

        if ($data['code'] == 'success') {
            return $data['result'];
        }

        throw new \RuntimeException(json_encode($data['result']));
    }
}
