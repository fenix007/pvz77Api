<?php

namespace SpExt\Pvz;

use SpExt\ApiWrapper\Model\Config;

trait PvzClient
{
    /** @var  Config */
    protected $config;

    /** @var  Pvz77Client */
    protected $pvz;

    /** @var  Pvz77Client */
    protected $pvzWithoutCache;

    public function __construct()
    {
        $this->config = $this->getPvz77Config();
        $this->pvz = new Pvz77Client($this->config);

        $configWithoutCache = clone $this->config;
        $configWithoutCache->disableCache();

        $this->pvzWithoutCache = new Pvz77Client($configWithoutCache);
    }

    /**
     * Return pvz77 api config
     *
     * @return array
     */
    protected function getPvz77Config()
    {
        return Config::create(include __DIR__ . '/../../pvzConfig.php');
    }
}
