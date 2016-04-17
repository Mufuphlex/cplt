<?php

namespace Mufuphlex\Cplt\Container\Cache;

/**
 * Interface CheckStrategyInterface
 * @package Mufuphlex\Cplt\Container\Cache
 */
interface CheckStrategyInterface
{
    /**
     * @return bool
     */
    public function check();

    /**
     * @return int
     */
    public function getVolumeDiff();
}