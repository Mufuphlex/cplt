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

    public function testCheck()
    {
        $this->falseCheckStrategy();
        $this->trueCheckStrategy();
    }

    private function getCache()
    {
        return static::getMock('\Mufuphlex\Cplt\Container\CacheInterface');
    }

    private function getCleanupStrategy()
    {
        return static::getMock('\Mufuphlex\Cplt\Container\Cache\CleanupStrategyInterface');
    }

    private function falseCheckStrategy()
    {
        $guard = $this->getGuardForCheck($this->getCache(), $this->getCheckStrategy(true));
        $this->assertFalse($guard->check());
    }

    private function trueCheckStrategy()
    {
        $guard = $this->getGuardForCheck($this->getCache(), $this->getCheckStrategy(false));
        $this->assertTrue($guard->check());
    }

    private function getCheckStrategy($return)
    {
        $strategy = static::getMock('\Mufuphlex\Cplt\Container\Cache\CheckStrategyInterface');
        $strategy
            ->expects(static::once())
            ->method('check')
            ->willReturn($return);

        return $strategy;
    }

    private function getGuardForCheck($cache, $checkStrategy)
    {
        $guard = new GuardDummy($cache);
        $reflection = new \ReflectionClass($guard);
        $property = $reflection->getProperty('checkStrategies');
        $property->setAccessible(true);
        $property->setValue($guard, array($checkStrategy));
        $guard->setCleanupStrategy($this->getCleanupStrategy());
        return $guard;
    }
}