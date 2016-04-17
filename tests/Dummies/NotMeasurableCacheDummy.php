<?php

namespace Mufuphlex\Tests\Cplt\Dummies;

use Mufuphlex\Cplt\Container\CacheInterface;

/**
 * Class NotMeasurableCacheDummy
 * @package Mufuphlex\Tests\Cplt\Dummies
 * Just a dummy for test
 */
class NotMeasurableCacheDummy implements CacheInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @param int $expirationTime
     * @return mixed
     */
    public function set($key, $value, $expirationTime = 0)
    {
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function delete($key)
    {
    }

    /**
     * @param  void
     * @return mixed
     */
    public function clear()
    {
    }
}