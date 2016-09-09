<?php

use Mufuphlex\Cache\CachePhpNative;
use Mufuphlex\Cplt\Container\DecoratorCached;
use Mufuphlex\Cplt\ContainerBuilderDefault;
use Mufuphlex\Cplt\Daemon\DaemonInterface;
use Mufuphlex\Cplt\Daemon\SocketDaemon;
use Mufuphlex\Cplt\InputProcessor\SocketInputProcessor;
use Mufuphlex\Sake\SocketListener;
use Mufuphlex\Textonic\Tokenizer\TokenizerEn;

require_once '../vendor/autoload.php';

function getPort(array $argv)
{
    if (!isset($argv[1]) || !is_numeric($argv[1])) {
        return require_once 'port.php';
    }

    return $argv[1];
}

function getFactoryMethod(array $argv)
{
    $method = 'makeDemo';

    if (in_array('--cache', $argv)) {
        $method .= 'Cached';
    }

    return $method;
}

/**
 * @param $port
 * @return DaemonInterface
 */
function getNamespacedDemoDaemon($port)
{
    $tokenizer = new TokenizerEn();
    $builder = new ContainerBuilderDefault($tokenizer);
    $container = $builder->build('');
    $container = new DecoratorCached($container, new CachePhpNative());

    $tokens = array(
        'animals' => array(
            'cat',
            'dog',
            'bat',
        ),
        'furniture' => array(
            'table',
            'chair',
            'bed',
        ),
        'stationary' => array(
            'pen',
            'pencil',
        )
    );

    foreach ($tokens as $namespace => $data) {
        foreach ($data as $item) {
            $container->addToken($item, $namespace);
        }
    }

    return getContainerDaemon($container, $port);
}

function getContainerDaemon($container, $port)
{
    $socketListener = new SocketListener('127.0.0.1', $port);
    $inputProcessor = new SocketInputProcessor($container);
    $socketListener->setInputProcessor($inputProcessor);
    return new SocketDaemon($socketListener);
}