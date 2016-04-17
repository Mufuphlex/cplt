<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\CachePhpNative;

class CachePhpNativeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $cache
     * @param $key
     * @param $value
     * @dataProvider cacheDataProvider
     */
    public function testSetGet($cache, $key, $value)
    {
        $cache->set($key, $value);
        $this->assertSame($value, $cache->get($key));
    }

    /**
     * @param $cache
     * @param $key
     * @param $value
     * @dataProvider cacheDataProvider
     */
    public function testExpiration($cache, $key, $value)
    {
        $cache->set($key, $value, 1);
        $this->assertSame($value, $cache->get($key));
        sleep(2);
        $this->assertNull($cache->get($key));
    }

    /**
     * @param $cache
     * @param $key
     * @param $value
     * @dataProvider cacheDataProvider
     */
    public function testDelete($cache, $key, $value)
    {
        $cache->set($key, $value);
        $this->assertSame($value, $cache->get($key));
        $cache->delete($key);
        $this->assertNull($cache->get($key));
    }

    /**
     * @param $cache
     * @param $key
     * @param $value
     * @dataProvider cacheDataProvider
     */
    public function testClear($cache, $key, $value)
    {
        $cache->set($key, $value);
        $this->assertSame($value, $cache->get($key));
        $cache->clear();
        $this->assertNull($cache->get($key));
    }

    public function testGetCount()
    {
        $cache = new CachePhpNative();
        $this->assertSame(0, $cache->getCount());
        $key = 'key';
        $cache->set($key, 'value');
        $this->assertSame(1, $cache->getCount());
        $cache->delete($key);
        $this->assertSame(0, $cache->getCount());
    }

    public function testGetVolume()
    {
        $cache = new CachePhpNative();
        $this->assertSame(6, $cache->getVolume());
        $key = 'key';
        $cache->set($key, 'value');
        $this->assertSame(54, $cache->getVolume());
        $cache->delete($key);
        $this->assertSame(6, $cache->getVolume());
    }

    public function cacheDataProvider()
    {
        return array(array(
            new CachePhpNative(),
            'key',
            'value',
        ));
    }
}