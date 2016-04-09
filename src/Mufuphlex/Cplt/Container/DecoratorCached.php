<?php

namespace Mufuphlex\Cplt\Container;

use Mufuphlex\Cplt\ContainerInterface;

/**
 * Class DecoratorCached
 * @package Mufuphlex\Cplt\Container
 */
class DecoratorCached implements ContainerInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var CacheInterface */
    private $cache;

    /**
     * DecoratorCached constructor.
     * @param ContainerInterface $container
     * @param CacheInterface $cache
     */
    public function __construct(ContainerInterface $container, CacheInterface $cache)
    {
        $this->container = $container;
        $this->cache = $cache;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function addToken($token)
    {
        $this->container->addToken($token);
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->container->getData();
    }

    /**
     * @param string $term
     * @return array
     */
    public function find($term)
    {
        $cache = $this->cache->get($term);

        if ($cache !== null) {
            return $cache;
        }

        $result = $this->container->find($term);
        $this->cache->set($term, $result);
        return $result;
    }
}