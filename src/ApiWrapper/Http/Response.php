<?php

namespace SpExt\ApiWrapper\Http;


/**
 * Class Response
 * @package Fenix007\Wrapper\HttpClient
 */
class Response extends  \GuzzleHttp\Psr7\Response
{
    public function getContentOrNull()
    {
        if ($this->getStatusCode() !== 200) {
            return null;
        }

        if (!$content = $this->getBody()->getContents()) {
            return null;
        }

        if (!$content = $this->checkContent($content)) {
            return null;
        }

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string|bool
     */
    protected function checkContent($content)
    {
        if (strpos($content, 'captchaSound') !== false) {
            return false;
        }

        return $content;
    }

    public static function createFromPsrResponse(\GuzzleHttp\Psr7\Response $response)
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }
}
