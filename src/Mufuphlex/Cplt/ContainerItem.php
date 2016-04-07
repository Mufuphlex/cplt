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
        if ($this->ancestors) {
            throw new \LogicException('Can not redefine $ancestors');
        }

        $this->ancestors = $ancestors;
        return $this;
    }
}