<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\Cache\HitManager;

class HitManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testIncAndRemove()
    {
        $hitManager = new HitManager();
        $emptyArray = array();
        $this->assertHitsCounterValue($hitManager, $emptyArray);
        $this->assertHitsTimerValue($hitManager, $emptyArray);
        $key = 'key';
        $hitManager->inc($key);
        $this->assertHitsCounterValue($hitManager, array($key => 1));
        $this->assertHitsTimerValue($hitManager, array($key => 0));
        $hitManager->remove($key);
        $this->assertHitsCounterValue($hitManager, $emptyArray);
        $this->assertHitsTimerValue($hitManager, $emptyArray);
    }

    public function testClear()
    {
        $hitManager = new HitManager();
        $key = 'key';
        $hitManager->inc($key);
        $this->assertHitsCounterValue($hitManager, array($key => 1));
        $this->assertHitsTimerValue($hitManager, array($key => 0));
        $hitManager->clear();
        $emptyArray = array();
        $this->assertHitsCounterValue($hitManager, $emptyArray);
        $this->assertHitsTimerValue($hitManager, $emptyArray);
    }

    public function testGetLessPopularKeys()
    {
        $hitManager = new HitManager();
        $key1 = 'key1';
        $hitManager->inc($key1);
        $hitManager->inc($key1);
        $key2 = 'key2';
        $hitManager->inc($key2);
        $keys = $hitManager->getLessPopularKeys(1);
        $this->assertSame(array($key2), $keys);
        $keys = $hitManager->getLessPopularKeys(2);
        $this->assertSame(array($key2, $key1), $keys);
    }

    private function assertHitsCounterValue($hitManager, $expected)
    {
        $this->assertPrivateValue($hitManager, 'hitsCounter', $expected);
    }

    private function assertHitsTimerValue($hitManager, $expected)
    {
        $this->assertPrivateValue($hitManager, 'hitsTimer', $expected);
    }

    private function assertPrivateValue($object, $property, $expected)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $this->assertEquals($expected, $property->getValue($object));
    }
}