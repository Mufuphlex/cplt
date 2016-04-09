<?php

namespace Mufuphlex\Cplt;

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
     * @param $port
     * @return DaemonInterface
     */
    public static function makeDemo($text, $port)
    {
        $container = static::makeContainer($text);
        $socketListener = static::makeSocketListener($container, $port);
        $daemon = static::makeDaemon($socketListener);
        return $daemon;
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
}