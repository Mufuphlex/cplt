<?php

namespace Mufuphlex\Tests\Cplt;

abstract class CacheTestCase extends TestCase
{
    protected function getHitManagerMock()
    {
        return static::getMock('\Mufuphlex\Cplt\Container\Cache\HitManagerInterface');
    }

    protected function getCacheInitialMock()
    {
        return static::getMockBuilder('\Mufuphlex\Cplt\Container\CachePhpNative')->disableOriginalConstructor()->getMock();
    }
}