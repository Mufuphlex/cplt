<?php

namespace Mufuphlex\Cplt\Container\Cache;
use Mufuphlex\Cplt\Container\CacheInterface;

/**
 * Class Guard
 * @package Mufuphlex\Cplt\Container\Cache
 */
class Guard implements GuardInterface
{
    /** How many keys to consider on each single cleanup */
    const CLEANUP_FRACTION = 0.1;

    /** How many times the volume difference must be multiplied for preventive cleanup */
    const CLEANUP_SIZE_ALLOWANCE = 3;

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

        $count = $this->cache->getCount();

        if ($count === 0) {
            return true;
        }

        $volume = $this->cache->getVolume();
        $diff = $this->maxVolume - $volume;

        \Mufuphlex\Logger::log(
            'Cache size '.$volume.'; diff: '.$diff.'; cnt: '.($count).'; avg key size: '.round($volume / $count, 2)
        );

        if ($diff >= 0) {
            return true;
        }

        $this->cleanup($diff);
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

    /**
     * @param int $diff
     * @return bool
     */
    private function cleanup($diff)
    {
        if (!$this->hitManager) {
            return false;
        }

        //@TODO Implement strategies - count and cleanup
        $numOfKeys = $this->getCountOfKeysToBeCleaned($diff);
        $keys = $this->hitManager->getLessPopularKeys($numOfKeys);

        if (!$keys) {
            \Mufuphlex\Logger::log('No suitable keys from hitManager');
            //@TODO Partial dichotomy cleanup is required
            return false;
        }

        print_r($keys);

        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
    }

    /**
     * @param int $diff
     * @return int
     */
    private function getCountOfKeysToBeCleaned($diff)
    {
        $avgKeyVolume = $this->cache->getVolume() / $this->cache->getCount();
        $numOfKeysToBeCleaned = ceil(abs($diff / $avgKeyVolume));
        return (round($numOfKeysToBeCleaned * static::CLEANUP_SIZE_ALLOWANCE));
    }
}