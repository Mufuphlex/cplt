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
     * @param string $namespace
     * @return $this
     */
    public function addToken($token, $namespace = '');

    /**
     * @param string $namespace
     * @return array
     */
    public function getData($namespace = '');

    /**
     * @param string $term
     * @param string $namespace
     * @return array
     */
    public function find($term, $namespace = '');
}