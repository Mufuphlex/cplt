<?php

namespace Mufuphlex\Cplt\Container\Cache;

use Mufuphlex\Cplt\Container\CacheInterface;

/**
 * Class GuardAbstract
 * @package Mufuphlex\Cplt\Container\Cache
 */
abstract class GuardAbstract implements GuardInterface
{
    /** @var CacheInterface */
    protected $cache;

    /** @var array [CheckStrategyInterface] */
    protected $checkStrategies = array();

    /** @var CleanupStrategyInterface */
    protected $cleanupStrategy;

    /**
     * GuardAbstract constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param CacheInterface $cache
     * @return $this
     */
    public function setCache(CacheInterface $cache)
    {
        if ($this->cache !== null) {
            throw new \RuntimeException('Can not redefine $cache');
        }

        $this->cache = $cache;
        return $this;
    }

    /**
     * @param CleanupStrategyInterface $cleanupStrategy
     * @return $this
     */
    public function setCleanupStrategy(CleanupStrategyInterface $cleanupStrategy)
    {
        if ($this->cleanupStrategy !== null) {
            throw new \RuntimeException('Can not redefine $cache');
        }

        $this->cleanupStrategy = $cleanupStrategy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function check()
    {
//        $ts = -microtime(true);
        /** @var CheckStrategyInterface $checkStrategy */
        foreach ($this->checkStrategies as $checkStrategy) {
            if ($checkStrategy->check()) {
//                \Mufuphlex\Logger::log(round(microtime(true) + $ts, 5)."\t".'Check positive, cleanup.. ');
                $this->cleanup($checkStrategy);
                return false;
            }
        }
//        \Mufuphlex\Logger::log(round(microtime(true) + $ts, 5)."\t".'Check negative');
        return true;
    }

    /**
     * @param CheckStrategyInterface $checkStrategy
     * @return mixed
     */
    protected function cleanup(CheckStrategyInterface $checkStrategy)
    {
        return $this->cleanupStrategy->cleanup($this->cache, $checkStrategy);
    }
}