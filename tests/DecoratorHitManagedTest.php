<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\Cache\DecoratorHitManaged;

class DecoratorHitManagedTest extends CacheTestCase
{
    public function testGet()
    {
        $key = 'key';
        $value = 'value';
        $cache = $this->getCacheInitialMock();
        $cache
            ->expects(static::once())
            ->method('get')
            ->with($key)
            ->willReturn($value);

        $decorator = $this->getDecoratorForMethod('inc', array($key), $cache);
        $this->assertEquals($value, $decorator->get($key));
    }

    public function testDelete()
    {
        $key = 'key';
        $decorator = $this->getDecoratorForMethod('remove', array($key));
        $decorator->delete($key);
    }

    public function testClear()
    {
        $decorator = $this->getDecoratorForMethod('clear');
        $decorator->clear();
    }

    public function testSet()
    {
        $key = 'key';
        $value = 'value';
        $expiration = 100;
        $cache = $this->getCacheInitialMock();
        $invokator = $cache
            ->expects(static::once())
            ->method('set');

        $args = array($key, $value, $expiration);
        call_user_func_array(array($invokator, 'with'), $args);

        $decorator = $this->getDecoratorForMethod(null, $args, $cache);
        $decorator->set($key, $value, $expiration);
    }

    public function testGetVolume()
    {
        $volume = mt_rand(100, 999);
        $cache = $this->getCacheInitialMock();
        $cache
            ->expects(static::once())
            ->method('getVolume')
            ->willReturn($volume);

        $decorator = $this->getDecoratorForMethod(null, null, $cache);
        $this->assertSame($volume, $decorator->getVolume());
    }

    public function testGetCount()
    {
        $count = mt_rand(10,99);
        $cache = $this->getCacheInitialMock();
        $cache
            ->expects(static::once())
            ->method('getCount')
            ->willReturn($count);

        $decorator = $this->getDecoratorForMethod(null, null, $cache);
        $this->assertSame($count, $decorator->getCount());
    }

    private function getDecoratorForMethod($method, $args = null, $cache = null)
    {
        if ($cache === null) {
            $cache = $this->getCacheInitialMock();
        }

        $hitManager = $this->getHitManagerWithMethod($method, $args);
        return $this->getDecorator($cache, $hitManager);
    }

    private function getDecorator($cache, $hitManager)
    {
        return new DecoratorHitManaged($cache, $hitManager);
    }

    private function getHitManagerWithMethod($method, $args = null)
    {
        $hitManager = $this->getHitManagerMock();

        if ($method !== null) {
            $invokator = $hitManager
                ->expects(static::once())
                ->method($method);

            if ($args !== null) {
                call_user_func_array(array($invokator, 'with'), $args);
            }
        }

        return $hitManager;
    }
}