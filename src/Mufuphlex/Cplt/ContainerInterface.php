<?php

namespace Mufuphlex\Cplt;

/**
 * Interface ContainerInterface
 * @package Mufuphlex\Cplt
 */
interface ContainerInterface
{
    /**
     * @param string $token
     * @return $this
     */
    public function addToken($token);

    /**
     * @return array
     */
    public function getData();

    /**
     * @param string $term
     * @return array
     */
    public function find($term);
}