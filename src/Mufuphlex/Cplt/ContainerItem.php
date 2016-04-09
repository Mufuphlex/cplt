<?php

namespace Mufuphlex\Cplt;

/**
 * Class ContainerItem
 * @package Mufuphlex\Cplt
 */
class ContainerItem implements ContainerItemInterface
{
    /** @var string */
    protected $value = '';

    /** @var int */
    protected $popularity = 0;

    /** @var array */
    protected $ancestors = array();

    /**
     * @param string $token
     * @return $this
     */
    public function setValue($token)
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('$token must be a string, '.gettype($token).' given');
        }

        $this->value = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $popularity
     * @return $this
     */
    public function setPopularity($popularity)
    {
        if (!is_integer($popularity)) {
            throw new \InvalidArgumentException('$popularity must be an integer, '.gettype($popularity).' given');
        }

        $this->popularity = $popularity;
        return $this;
    }

    /**
     * @return int
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @return &array
     */
    public function &getAncestors()
    {
        $ancestors = &$this->ancestors;
        return $ancestors;
    }

    /**
     * @param array $ancestors
     * @return $this
     */
    public function setAncestors(array $ancestors)
    {
        if ($this->ancestors !== array()) {
            throw new \LogicException('Can not redefine $ancestors');
        }

        $this->ancestors = $ancestors;
        return $this;
    }
}