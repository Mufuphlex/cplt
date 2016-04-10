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
    private $maxVolume = 0;

    /**
     * @return bool
     */
    public function check()
    {
        if (!$this->maxVolume) {
            return true;
        }

        if ($this->cache->getVolume() <= $this->maxVolume) {
            return true;
        }

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
     * @param int $volume
     * @return $this
     */
    public function setMaxVolume($volume)
    {
        $this->maxVolume = $volume;
        return $this;
    }
}