<?php

namespace Mufuphlex\Cplt\Container;
use Mufuphlex\Cplt\Container\Cache\GuardInterface;
use Mufuphlex\Cplt\Container\Cache\HitManagerInterface;

/**
 * Class CachePhpNative
 * @package Mufuphlex\Cplt\Container
 */
class CachePhpNative implements CacheInterface, MeasurableCacheInterface
{
    const DEFAULT_EXPIRATION_TIME = 0;

    /** @var array */
    private $storage = array();

    /** @var HitManagerInterface */
    private $hitManager;

    /** @var int */
    private $defaultExpirationTime;

    /**
     * CachePhpNative constructor.
     * @param int $defaultExpirationTime
     */
    public function __construct($defaultExpirationTime = self::DEFAULT_EXPIRATION_TIME)
    {
        $this->defaultExpirationTime = $defaultExpirationTime;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expirationTime
     * @return mixed
     */
    public function set($key, $value, $expirationTime = 0)
    {
        $this->storage[$key] = array(
            'd' => $value,
            'e' => ($expirationTime ? time() + $expirationTime : $this->defaultExpirationTime),
        );

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (!isset($this->storage[$key])) {
            return null;
        }

        if ($this->storage[$key]['e'] && (time() > $this->storage[$key]['e'])) {
            $this->delete($key);
            return null;
        }

//        $this->log('HIT '.$key);
        return $this->storage[$key]['d'];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function delete($key)
    {
        if (isset($this->storage[$key])) {
            unset($this->storage[$key]);
        }
    }

    /**
     * @param  void
     * @return $this
     */
    public function clear()
    {
        $this->storage = array();
        return $this;
    }

    /**
     * @return int
     */
    public function getVolume()
    {
        return strlen(serialize($this->storage));
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->storage);
    }

    private function log($msg)
    {
        \Mufuphlex\Logger::log($msg);
    }
}