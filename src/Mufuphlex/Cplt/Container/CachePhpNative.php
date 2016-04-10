<?php

namespace Mufuphlex\Cplt\Container;
use Mufuphlex\Cplt\Container\Cache\GuardInterface;
use Mufuphlex\Cplt\Container\Cache\HitManagerInterface;

/**
 * Class CachePhpNative
 * @package Mufuphlex\Cplt\Container
 */
class CachePhpNative implements CacheInterface
{
    const DEFAULT_EXPIRATION_TIME = 0;

    /** @var array */
    private $storage = array();

    /** @var GuardInterface */
    private $guard;

    /** @var HitManagerInterface */
    private $hitManager;

    /** @var int */
    private $defaultExpirationTime;

    /**
     * CachePhpNative constructor.
     * @param HitManagerInterface $hitManager
     * @param GuardInterface $guard
     * @param int $defaultExpirationTime
     */
    public function __construct(HitManagerInterface $hitManager, GuardInterface $guard, $defaultExpirationTime = self::DEFAULT_EXPIRATION_TIME)
    {
        $this->hitManager = $hitManager;
        $this->guard = $guard;
        $this->defaultExpirationTime = $defaultExpirationTime;
        $this->guard->setCache($this);
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

        $this->guard->check();
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
            unset($this->storage[$key]);
            $this->hitManager->remove($key);
            return null;
        }

        $this->hitManager->inc($key);
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
            $this->hitManager->remove($key);
        }
    }

    /**
     * @param  void
     * @return $this
     */
    public function clear()
    {
        $this->storage = array();
        $this->hitManager->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function getVolume()
    {
        return strlen(serialize($this->storage));
    }
}