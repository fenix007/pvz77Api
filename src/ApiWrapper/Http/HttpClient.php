<?php

namespace SpExt\ApiWrapper\Http;

use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SpExt\ApiWrapper\Model\Config;

class HttpClient extends Client
{
    /** @var Config  */
    protected $clientConfig;

    /**
     * HttpClient constructor.
     *
     * @param Config $config
     * @param bool   $disableCache
     */
    public function __construct(Config $config, $disableCache = false)
    {
        $this->clientConfig = $config;
        if ($disableCache) {
            $this->disableCache();
        }

        $guzzleConfig = $config->guzzleConfig;

        $stack = HandlerStack::create();

        if ($config->cacheEnabled) {
//            $guzzleConfig['options']['cache']['enabled'] = true;
//            $guzzleConfig['options']['cache']['path'] = $config->cachePath;
            $stack->push($this->cacheMiddleware(), 'cache');
        }

        if ($config->logEnabled) {
            $stack->push($this->logMiddleware(), 'logger');
        }

//        $stack->push($this->charsetMiddleware(), 'charset');

        $guzzleConfig['handler']= $stack;

        parent::__construct($guzzleConfig);
    }

    /**
     * @return CacheMiddleware
     */
    protected function cacheMiddleware()
    {
        $store = new DoctrineCacheStorage(
            new FilesystemCache($this->clientConfig->cachePath)
        );

        $cacheMiddleware = new CacheMiddleware(new GreedyCacheStrategy($store, $this->clientConfig->cacheTime));
        $cacheMiddleware->setHttpMethods($this->clientConfig->cachedMethods);

        return $cacheMiddleware;
    }

    protected function logMiddleware()
    {
        $logger = new Logger('logger');
        $logger->pushHandler(new StreamHandler($this->clientConfig->logPath), Logger::API);

        return Middleware::log(
            $logger,
            new MessageFormatter('{req_body} - {res_body}')
        );
    }

    public function createRequest(Request $request, ResponseMapper $mapper = null)
    {
        $method = $request->getMethod();

        /** @var \Psr\Http\Message\ResponseInterface $response */
        $response = $this->$method($request);

        $response = $this->preProcessResponse($response);

        if ($mapper) {
            $response = $mapper->map($response);
        }

        return $response;
    }

    protected function preProcess(Request $request)
    {
        $url = $this->preProcessUrl($request->getPath());

        $parameters = $this->preProcessParameters($request->getParameters());

        return [$url, $parameters];
    }

    protected function preProcessUrl($url)
    {
        return $url;
    }

    protected function preProcessParameters(array $parameters)
    {
        return $parameters;
    }

    public function __call($name, $arguments)
    {
        if (! in_array($name, Request::METHODS)) {
            throw new \BadMethodCallException("There is not method $name in HtppClient class");
        }

        $request = $arguments[0];

        list($url, $parameters) = $this->preProcess($request);

        return Response::createFromPsrResponse(
            $this->getContent(strtolower($name), [$url, $parameters])
        );
    }

    protected function getContent($name, $arguments)
    {
        return parent::__call(strtolower($name), $arguments);
    }

    protected function preProcessResponse($response)
    {
        return $response;
    }

    protected function disableCache()
    {
        $this->clientConfig->cacheEnabled = false;
    }

    protected function enableCache()
    {
        $this->clientConfig->cacheEnabled = true;
    }
}
