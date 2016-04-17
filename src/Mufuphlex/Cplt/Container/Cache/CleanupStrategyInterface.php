<?php

namespace Mufuphlex\Cplt\Container\Cache;
use Mufuphlex\Cplt\Container\CacheInterface;

/**
 * Interface CleanupStrategyInterface
 * @package Mufuphlex\Cplt\Container\Cache
 */
interface CleanupStrategyInterface
{
    /**
     * @param CacheInterface $cache
     * @param CheckStrategyInterface $checkStrategy
     * @return mixed
     */
    public function cleanup(CacheInterface $cache, CheckStrategyInterface $checkStrategy);
}