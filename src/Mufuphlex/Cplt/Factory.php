<?php

namespace Mufuphlex\Cplt;

use Mufuphlex\Cplt\Container\CachePhpNative;
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
     * @return DaemonInterface
     */
    public static function makeDemoCached($text, $port)
    {
        $container = static::makeContainer($text);
        $container = new DecoratorCached($container, new CachePhpNative());
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