<?php

namespace Tests\Pvz;

use SebastianBergmann\GlobalState\RuntimeException;
use SpExt\ApiWrapper\Model\Config;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var  Config */
    protected $configWithCache;

    /** @var  Config */
    protected $configWithoutCache;

    /**
     * Get config from file
     * 
     * @return array
     */
    protected function getConfigFromFile()
    {
        if (! file_exists(CONFIG_FILE)) {
            throw new RuntimeException('File ' . CONFIG_FILE . ' does not exist');
        }

        return include CONFIG_FILE;
    }

    protected function createConfigWithCache()
    {
        if ($this->configWithCache) {
            return $this->configWithCache;
        }

        $this->configWithCache = Config::create($this->getConfigFromFile())
            ->enableCache();

        return $this->configWithCache;
    }

    protected function createConfigWithoutCache()
    {
        if ($this->configWithoutCache) {
            return $this->configWithoutCache;
        }

        $this->configWithoutCache = Config::create($this->getConfigFromFile())
            ->disableCache();

        return $this->configWithoutCache;
    }

    protected function assertIsSubArray(array $subArray, array $array)
    {
        $this->assertEquals($subArray, array_intersect_key($array, $subArray));
    }

    protected function assertArrayExcept(array $subArray, array $array, array $except)
    {
        foreach ($except as $key) {
            unset($subArray[$key]);
        }

        $this->assertArraySubset($subArray, $array);
    }
}
