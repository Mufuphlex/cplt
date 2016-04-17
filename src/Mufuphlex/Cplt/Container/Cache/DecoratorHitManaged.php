<?php

namespace Mufuphlex\Cplt\Container\Cache;

use Mufuphlex\Cplt\Container\CacheInterface;
use Mufuphlex\Cplt\Container\MeasurableCacheInterface;

/**
 * Class DecoratorHitManaged
 * @package Mufuphlex\Cplt\Container\Cache
 */
class DecoratorHitManaged implements CacheInterface, MeasurableCacheInterface
{
    /** @var CacheInterface */
    private $cache;

    /** @var HitManagerInterface */
    private $hitManager;

    /**
     * DecoratorHitManaged constructor.
     * @param CacheInterface $cache
     * @param HitManagerInterface $hitManager
     */
    public function __construct(CacheInterface $cache, HitManagerInterface $hitManager)
    {
        $this->cache = $cache;
        $this->hitManager = $hitManager;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expirationTime
     * @return mixed
     */
    public function set($key, $value, $expirationTime = 0)
    {
        return $this->cache->set($key, $value, $expirationTime);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $result = $this->cache->get($key);

        if ($result !== null) {
            $this->hitManager->inc($key);
        }

        return $result;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function delete($key)
    {
        $this->cache->delete($key);
        $this->hitManager->remove($key);
    }

    /**
     * @param  void
     * @return mixed
     */
    public function clear()
    {
        $this->hitManager->clear();
        return $this->cache->clear();
    }

    /**
     * @return int
     */
    public function getVolume()
    {
        return $this->cache->getVolume();
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->cache->getCount();
    }
}