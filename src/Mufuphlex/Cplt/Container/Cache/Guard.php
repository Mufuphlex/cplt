<?php

namespace Mufuphlex\Cplt\Container\Cache;
use Mufuphlex\Cplt\Container\CacheInterface;

/**
 * Class Guard
 * @package Mufuphlex\Cplt\Container\Cache
 */
class Guard implements GuardInterface
{
    /** @var CacheInterface */
    private $cache;

    /** @var int */
    private $maxVolume = 0;

    /** @var HitManagerInterface */
    private $hitManager;

    /**
     * @return bool
     */
    public function check()
    {
        if (!$this->maxVolume) {
            return true;
        }

        $diff = $this->maxVolume - $this->cache->getVolume();

        if ($diff >= 0) {
            return true;
        }

        \Mufuphlex\Logger::log('Oversize: '.$diff.'. Need to do smth');
        $this->cleanup();
        return false;
    }

    /**
     * @param CacheInterface $cache
     * @return mixed
     */
    public function setCache(CacheInterface $cache)
    {
        if ($this->cache !== null) {
            throw new \LogicException('Can not redefine $cache');
        }

        $this->cache = $cache;
        return $this;
    }

    /**
     * @param HitManagerInterface $hitManager
     * @return mixed
     */
    public function setHitManager(HitManagerInterface $hitManager)
    {
        if ($this->hitManager !== null) {
            throw new \LogicException('Can not redefine $hitManager');
        }

        $this->hitManager = $hitManager;
        return $this;
    }

    /**
     * @param int $volume
     * @return $this
     */
    public function setMaxVolume($volume)
    {
        $this->maxVolume = $volume;
        return $this;
    }

    private function cleanup()
    {
        if (!$this->hitManager) {
            return false;
        }

        $keys = $this->hitManager->getLessPopularKeys(5);  //@TODO num of keys basing on size distribution

        if (!$keys) {
            var_dump($this->cache);
            return false;
        }

        print_r($keys);

        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
    }
}