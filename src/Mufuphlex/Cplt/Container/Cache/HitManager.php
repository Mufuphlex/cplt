<?php

namespace Mufuphlex\Cplt\Container\Cache;

/**
 * Class HitManager
 * @package Mufuphlex\Cplt\Container\Cache
 */
class HitManager implements HitManagerInterface
{
    const TIMER_OFFSET = 1500000000;

    /** @var array */
    private $hitsCounter = array();

    /** @var array */
    private $hitsTimer = array();

    /**
     * @param string $key
     * @return $this
     */
    public function inc($key)
    {
        $this->incHitCounter($key);
        $this->updateTimer($key);
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove($key)
    {
        if (isset($this->hitsCounter[$key])) {
            unset($this->hitsCounter[$key]);
        }

        if (isset($this->hitsTimer[$key])) {
            unset($this->hitsTimer[$key]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->hitsCounter = array();
        $this->hitsTimer = array();
        return $this;
    }

    /**
     * @param string $key
     */
    private function incHitCounter($key)
    {
        if (!isset($this->hitsCounter[$key])) {
            $this->hitsCounter[$key] = 0;
        }

        $this->hitsCounter[$key]++;
    }

    /**
     * @param string $key
     */
    private function updateTimer($key)
    {
        $this->hitsTimer[$key] = time() - static::TIMER_OFFSET;
    }
}