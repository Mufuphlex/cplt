<?php

namespace Mufuphlex\Cplt\Container\Cache\Guard;

use Mufuphlex\Cplt\Container\Cache\CheckStrategy\Volume;
use Mufuphlex\Cplt\Container\Cache\CleanupStrategy\Popularity;
use Mufuphlex\Cplt\Container\Cache\HitManagerInterface;
use Mufuphlex\Cplt\Container\CacheInterface;

/**
 * Class HitManagedGuard
 * @package Mufuphlex\Cplt\Container\Cache\Guard
 */
class HitManagedGuard extends HitManagedGuardAbstract
{
    /**
     * HitManagedGuard constructor.
     * @param CacheInterface $cache
     * @param HitManagerInterface $hitManager
     * @param int $maxVolume
     */
    public function __construct(CacheInterface $cache, HitManagerInterface $hitManager, $maxVolume)
    {
        parent::__construct($cache);

        $checkStrategy = new Volume($this->cache);
        $checkStrategy->setMaxVolume($maxVolume);

        $this->checkStrategies = array($checkStrategy);

        $this->setCleanupStrategy(new Popularity($hitManager));
    }
}