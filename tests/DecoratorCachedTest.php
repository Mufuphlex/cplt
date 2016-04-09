<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\CachePhpNative;
use Mufuphlex\Cplt\Container\DecoratorCached;

class DecoratorCachedTest extends \PHPUnit_Framework_TestCase
{
    public function testAddToken()
    {
        $token = 'token';

        $container = static::getMock('\Mufuphlex\Cplt\ContainerInterface');
        $container
            ->expects(static::once())
            ->method('addToken')
            ->with($token);

        $cache = static::getMock('\Mufuphlex\Cplt\Container\CacheInterface');

        $container = new DecoratorCached($container, $cache);
        $container->addToken($token);
    }

    public function testGetData()
    {
        $container = static::getMock('\Mufuphlex\Cplt\ContainerInterface');
        $container
            ->expects(static::once())
            ->method('getData');

        $cache = static::getMock('\Mufuphlex\Cplt\Container\CacheInterface');

        $container = new DecoratorCached($container, $cache);
        $container->getData();
    }

    public function testFind()
    {
        $token = 'token';
        $result = array(
            'tokenizer',
        );

        $container = static::getMock('\Mufuphlex\Cplt\ContainerInterface');

        $container
            ->expects(static::once())
            ->method('find')
            ->with($token)
            ->willReturn($result);

        $container = new DecoratorCached($container, new CachePhpNative());
        $actualResult = $container->find($token);
        $this->assertEquals($result, $actualResult);
        $actualResult = $container->find($token);
        $this->assertEquals($result, $actualResult);
    }
}