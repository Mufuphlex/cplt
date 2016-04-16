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

    /** @var string */
    private $cacheKey = '';

    /**
     * DecoratorCached constructor.
     * @param ContainerInterface $container
     * @param CacheInterface $cache
     */
    public function __construct(ContainerInterface $container, CacheInterface $cache)
    {
        $this->container = $container;
        $this->cache = $cache;
        $this->cacheKey = uniqid();
    }

    /**
     * @param string $token
     * @param string $namespace
     * @return $this
     */
    public function addToken($token, $namespace = '')
    {
        $this->container->addToken($token, $namespace);
        return $this;
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function getData($namespace = '')
    {
        return $this->container->getData($namespace);
    }

    /**
     * @param string $term
     * @param string $namespace
     * @return array
     */
    public function find($term, $namespace = '')
    {
        $key = $this->makeKey($term, $namespace);
        $cache = $this->cache->get($key);

        if ($cache !== null) {
            return $cache;
        }

        $result = $this->container->find($term, $namespace);

        if (!empty($result)) {
            $this->cache->set($key, $result);
        }

        return $result;
    }

    /**
     * @param string $term
     * @param string $namespace
     * @return string mixed
     */
    private function makeKey($term, $namespace)
    {
        return implode('_', array($this->cacheKey, $namespace, $term));
    }
}