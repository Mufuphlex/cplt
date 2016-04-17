<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Tests\Cplt\Dummies\GuardDummy;

class GuardAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCache()
    {
        $guard = new GuardDummy();
        $this->assertSame($guard, $guard->setCache($this->getCache()));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Can not redefine $cache
     */
    public function testSetCacheFailsOnRedefine()
    {
        $cache = $this->getCache();
        $guard = new GuardDummy($cache);
        $guard->setCache($cache);
    }

    public function testSetCleanupStrategy()
    {
        $guard = new GuardDummy();
        $this->assertSame($guard, $guard->setCleanupStrategy($this->getCleanupStrategy()));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Can not redefine $cleanupStrategy
     */
    public function testSetCleanupStrategyFailsOnRedefine()
    {
        $cleanupStrategy = $this->getCleanupStrategy();
        $guard = new GuardDummy();
        $guard->setCleanupStrategy($cleanupStrategy);
        $guard->setCleanupStrategy($cleanupStrategy);
    }

    private function getCache()
    {
        return static::createMock('\Mufuphlex\Cplt\Container\CacheInterface');
    }

    private function getCleanupStrategy()
    {
        return static::createMock('\Mufuphlex\Cplt\Container\Cache\CleanupStrategyInterface');
    }
}