<?php

namespace Mufuphlex\Cplt\Container;

/**
 * Class CachePhpNative
 * @package Mufuphlex\Cplt\Container
 */
class CachePhpNative implements CacheInterface
{
    /** @var array */
    private $storage = array();

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
            'e' => ($expirationTime ? time() + $expirationTime : 0),
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
            unset($this->storage[$key]);
            return null;
        }

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
}