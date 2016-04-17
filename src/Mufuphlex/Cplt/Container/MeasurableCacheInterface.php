<?php

namespace Mufuphlex\Cplt\Container;

/**
 * Interface MeasurableCacheInterface
 * @package Mufuphlex\Cplt\Container
 */
interface MeasurableCacheInterface
{
    /**
     * @return int
     */
    public function getVolume();

    /**
     * @return int
     */
    public function getCount();
}