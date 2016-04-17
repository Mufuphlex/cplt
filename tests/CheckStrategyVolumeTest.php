<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\Cache\CheckStrategy\Volume;

class CheckStrategyVolumeTest extends TestCase
{
    public function testSetGetMaxVolume()
    {
        $strategy = $this->getStrategy();
        $maxValue = mt_rand(100, 999);
        $strategy->setMaxVolume($maxValue);
        $this->assertSame($maxValue, $strategy->getMaxVolume());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Can not redefine $maxVolume
     */
    public function testSetMaxVolumeFailsOnExistingValue()
    {
        $strategy = $this->getStrategy();
        $maxValue = mt_rand(100, 999);
        $strategy->setMaxVolume($maxValue);
        $maxValue = mt_rand(100, 999);
        $strategy->setMaxVolume($maxValue);
    }

    /**
     * @dataProvider notIntegerDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetMaxVolumeFailsOnNotInteger($value)
    {
        $strategy = $this->getStrategy();
        $strategy->setMaxVolume($value);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $maxVolume must be positive
     */
    public function testSetMaxVolumeFailsOnNegative()
    {
        $strategy = $this->getStrategy();
        $strategy->setMaxVolume(-1);
    }

    public function testGetVolumeDiff()
    {
        $volume = mt_rand(100, 999);
        $cache = static::createMock('\Mufuphlex\Cplt\Container\MeasurableCacheInterface');
        $cache
            ->expects(static::once())
            ->method('getVolume')
            ->willReturn($volume);

        $maxVolume = 50;

        $strategy = new Volume($cache);
        $this->assertEquals(0, $strategy->getVolumeDiff());
        
        $strategy->setMaxVolume($maxVolume);
        $this->assertEquals(($maxVolume - $volume), $strategy->getVolumeDiff());
    }

    private function getStrategy()
    {
        $cache = static::createMock('\Mufuphlex\Cplt\Container\MeasurableCacheInterface');
        $strategy = new Volume($cache);
        return $strategy;
    }
}