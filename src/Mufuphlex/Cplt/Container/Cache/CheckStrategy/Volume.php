<?php

namespace Mufuphlex\Cplt\Container\Cache\CheckStrategy;

use Mufuphlex\Cplt\Container\Cache\CheckStrategyInterface;
use Mufuphlex\Cplt\Container\MeasurableCacheInterface;

/**
 * Class Volume
 * @package Mufuphlex\Cplt\Container\Cache\CheckStrategy
 */
class Volume implements CheckStrategyInterface, VolumeInterface
{
    /**
     * @var MeasurableCacheInterface
     */
    protected $cache;

    /** @var int */
    protected $maxVolume = 0;

    /**
     * Volume constructor.
     * @param MeasurableCacheInterface $cache
     */
    public function __construct(MeasurableCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return bool
     */
    public function check()
    {
        if (!$this->maxVolume) {
            return false;
        }

        if (!$this->cache->getCount()) {
            return false;
        }

        $diff = $this->getVolumeDiff();
//        \Mufuphlex\Logger::log('  diff: '.$diff);
        return $diff < 0;
    }

    /**
     * @param int $maxVolume
     * @return $this
     */
    public function setMaxVolume($maxVolume)
    {
        if ($this->maxVolume) {
            throw new \RuntimeException('Can not redefine $maxVolume');
        }

        if (!is_integer($maxVolume)) {
            throw new \InvalidArgumentException('$maxVolume must be integer, '.gettype($maxVolume).' given');
        }

        if ($maxVolume < 0) {
            throw new \InvalidArgumentException('$maxVolume must be positive');
        }

        $this->maxVolume = $maxVolume;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxVolume()
    {
        return $this->maxVolume;
    }

    /**
     * @return int
     */
    public function getVolumeDiff()
    {
        if (!$this->maxVolume) {
            return 0;
        }

        return ($this->maxVolume - $this->cache->getVolume());
    }
}