<?php

namespace Mufuphlex\Cplt;

use Mufuphlex\Cache\Decorators\DecoratorGuarded;
use Mufuphlex\Cache\Decorators\DecoratorHitManaged;
use Mufuphlex\Cache\Guards\HitManaged\HitManagedGuard;
use Mufuphlex\Cache\HitManager;
use Mufuphlex\Cplt\Container\Cache;
use Mufuphlex\Cplt\Container\DecoratorCached;
use Mufuphlex\Cplt\Daemon\DaemonInterface;
use Mufuphlex\Cplt\Daemon\SocketDaemon;
use Mufuphlex\Cplt\InputProcessor\SocketInputProcessor;
use Mufuphlex\Sake\SocketListener;
use Mufuphlex\Sake\SocketListenerInterface;
use Mufuphlex\Textonic\Tokenizer\TokenizerEn;

/**
 * Class Factory
 * @package Mufuphlex\Cplt
 */
class Factory
{
    const DEFAULT_CACHE_SIZE = 41943040;   // 40*1024*1024 bytes

    /**
     * @param string $text
     * @param int $port
     * @return DaemonInterface
     */
    public static function makeDemo($text, $port)
    {
        $container = static::makeContainer($text);
        return static::getDaemonWithContainerOnPort($container, $port);
    }

    /**
     * @param string $text
     * @param int $port
     * @param int $size
     * @return DaemonInterface
     */
    public static function makeDemoCached($text, $port, $size = self::DEFAULT_CACHE_SIZE)
    {
        $container = static::makeContainer($text);
        $hitManager = new HitManager();
        $cache = new Cache();
        $cache = new DecoratorHitManaged($cache, $hitManager);
        $guard = new HitManagedGuard($cache, $hitManager, (int)$size);
        $cache = new DecoratorGuarded($cache, $guard);
        $container = new DecoratorCached($container, $cache);
        return static::getDaemonWithContainerOnPort($container, $port);
    }

    /**
     * @param string $text
     * @return ContainerInterface
     */
    private static function makeContainer($text)
    {
        $tokenizer = new TokenizerEn();
        $builder = new ContainerBuilderDefault($tokenizer);
        $container = $builder->build($text);
        return $container;
    }

    /**
     * @param SocketListenerInterface $socketListener
     * @return DaemonInterface
     */
    private static function makeDaemon(SocketListenerInterface $socketListener)
    {
        $daemon = new SocketDaemon($socketListener);
        return $daemon;
    }

    /**
     * @param ContainerInterface $container
     * @param $port
     * @return SocketListener
     */
    private static function makeSocketListener(ContainerInterface $container, $port)
    {
        $socketListener = new SocketListener('127.0.0.1', $port);
        $inputProcessor = new SocketInputProcessor($container);
        $socketListener->setInputProcessor($inputProcessor);
        return $socketListener;
    }

    /**
     * @param ContainerInterface $container
     * @param int $port
     * @return DaemonInterface
     */
    private static function getDaemonWithContainerOnPort(ContainerInterface $container, $port)
    {
        $socketListener = static::makeSocketListener($container, $port);
        $daemon = static::makeDaemon($socketListener);
        return $daemon;
    }
}