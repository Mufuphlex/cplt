<?php

namespace Mufuphlex\Cplt\Container\Cache\CheckStrategy;

/**
 * Interface VolumeInterface
 * @package Mufuphlex\Cplt\Container\Cache\CheckStrategy
 */
interface VolumeInterface
{
    /**
     * @param int $maxVolume
     * @return $this
     */
    public function setMaxVolume($maxVolume);

    /**
     * @return int
     */
    public function getMaxVolume();
}