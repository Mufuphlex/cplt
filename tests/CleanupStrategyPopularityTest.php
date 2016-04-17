<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\Cache\CleanupStrategy\Popularity;
use Mufuphlex\Tests\Cplt\Dummies\NotMeasurableCacheDummy;

class CleanupStrategyPopularityTest extends CacheTestCase
{
    public function testCleanup()
    {
        $this->cleanupFalse();
        $this->cleanupTrue();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Popularity cleanup strategy applicable to MeasurableCacheInterface only
     */
    public function testCleanupFailsOnNotMeasurableCache()
    {
        $hitManager = $this->getHitManagerMock();
        $strategy = new Popularity($hitManager);
        $cache = new NotMeasurableCacheDummy();
        $checkStrategy = static::createMock('\Mufuphlex\Cplt\Container\Cache\CheckStrategy\Volume');
        $strategy->cleanup($cache, $checkStrategy);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Popularity cleanup strategy applicable to Volume check strategy Interface only
     */
    public function testCleanupFailsOnNotVolumeStrategy()
    {
        $hitManager = $this->getHitManagerMock();
        $strategy = new Popularity($hitManager);
        $cache = $this->getCacheInitialMock();
        $checkStrategy = static::createMock('\Mufuphlex\Cplt\Container\Cache\CheckStrategyInterface');
        $strategy->cleanup($cache, $checkStrategy);
    }

    private function cleanupFalse()
    {
        $hitManager = $this->getHitManagerMock();
        $hitManager
            ->expects(static::once())
            ->method('getLessPopularKeys')
            ->willReturn(array());

        $cache = $this->getCache();
        $checkStrategy = $this->getCheckStrategy();

        $strategy = new Popularity($hitManager);
        $this->assertFalse($strategy->cleanup($cache, $checkStrategy));
    }

    private function cleanupTrue()
    {
        $hitManager = $this->getHitManagerMock();
        $hitManager
            ->expects(static::once())
            ->method('getLessPopularKeys')
            ->willReturn(array('key1', 'key2'));

        $cache = $this->getCache();
        $cache
            ->expects(static::exactly(2))
            ->method('delete')
            ->withConsecutive(array('key1'), array('key2'));

        $checkStrategy = $this->getCheckStrategy();

        $strategy = new Popularity($hitManager);
        $this->assertTrue($strategy->cleanup($cache, $checkStrategy));
    }

    private function getCache()
    {
        $cache = $this->getCacheInitialMock();
        $cache
            ->expects(static::once())
            ->method('getVolume')
            ->willReturn(10);
        $cache
            ->expects(static::once())
            ->method('getCount')
            ->willReturn(1);

        return $cache;
    }

    private function getCheckStrategy()
    {
        $checkStrategy = static::createMock('\Mufuphlex\Cplt\Container\Cache\CheckStrategy\Volume');
        $checkStrategy
            ->expects(static::once())
            ->method('getMaxVolume')
            ->willReturn(5);

        return $checkStrategy;
    }
}