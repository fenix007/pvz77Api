<?php

namespace SpExt\Pvz;

/**
 * Trait PvzSession
 * @package  SpExt\Pvz
 * @internal Config $clientConfig
 */
trait PvzSession
{
    private $withSessionId = true;

    private $sessionId;

    protected function getSessionId()
    {
        if ($this->sessionId) {
            return $this->sessionId;
        }

        $this->sessionId = $this->createSession(true);

        return $this->sessionId;
    }

    /**
     * Create session for next request
     * @throws \RuntimeException
     * @return string
     */
    protected function createSession($wrong = false)
    {
        $withSessionId = $this->withSessionId;
        $this->withSessionId = false;

        $result = $this->apiRequest('CreateSession', [
            'login'    => $this->clientConfig->login,
            'password' => $this->clientConfig->password
        ]);

        if (!isset($result['session_id'])) {
            throw new \RuntimeException('Error to create session');
        }

        $this->withSessionId = $withSessionId;

        return $wrong ? $result['session_id'] : $result['session_id'];
    }

    protected function updateSession()
    {
        $config = clone $this->clientConfig;
        $config->cacheEnabed = false;

        $client = new static($config);

        $this->sessionId = $client->createSession();
    }
}
