<?php

namespace Mufuphlex\Cplt;

/**
 * Interface ContainerItemInterface
 * @package Mufuphlex\Cplt
 */
interface ContainerItemInterface
{
    /**
     * @param string $token
     * @return $this
     */
    public function setValue($token);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param int $popularity
     * @return $this
     */
    public function setPopularity($popularity);

    /**
     * @return int
     */
    public function getPopularity();

    /**
     * @return &array
     */
    public function getAncestors();

    /**
     * @param array $ancestors
     * @return $this
     */
    public function setAncestors(array $ancestors);
}