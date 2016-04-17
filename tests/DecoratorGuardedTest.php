<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container\Cache\DecoratorGuarded;

class DecoratorGuardedTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $key = 'key';
        $decorator = $this->getDecoratorForMethod('get', array($key));
        $decorator->get($key);
    }

    public function testDelete()
    {
        $key = 'key';
        $decorator = $this->getDecoratorForMethod('delete', array($key));
        $decorator->delete($key);
    }

    public function testClear()
    {
        $decorator = $this->getDecoratorForMethod('clear');
        $decorator->clear();
    }

    /**
     * @dataProvider keyValueExpirationDataProvider
     */
    public function testSet($key, $value, $expiration, $guardCheckResult, $setCallCount)
    {
        $decorator = $this->getDecoratorForSet($guardCheckResult, array($key, $value, $expiration), $setCallCount);
        $decorator->set($key, $value, $expiration);
    }

    private function getCacheMock()
    {
        return static::getMock('\Mufuphlex\Cplt\Container\CacheInterface');
    }

    private function getCacheMockWithMethod($cache, $method, $arg = null, $callCount = 1)
    {
        $invokator = $cache
            ->expects(static::exactly($callCount))
            ->method($method);

        if ($arg !== null) {
            call_user_func_array(array($invokator, 'with'), $arg);
        }

        return $cache;
    }

    private function getGuardMock()
    {
        return static::getMock('\Mufuphlex\Cplt\Container\Cache\GuardInterface');
    }

    private function getDecorator($cache, $guard = null)
    {
        if ($guard === null) {
            $guard = $this->getGuardMock();
        }

        return new DecoratorGuarded($cache, $guard);
    }

    private function getDecoratorForMethod($method, $key = null)
    {
        $cache = $this->getCacheMock();
        return $this->getDecorator($this->getCacheMockWithMethod($cache, $method, $key));
    }

    private function getDecoratorForSet($guardCheckResult, $setArgs, $setCallCount)
    {
        $guard = $this->getGuardMock();
        $guard
            ->expects(static::once())
            ->method('check')
            ->willReturn($guardCheckResult);

        $cache = $this->getCacheMockWithMethod($this->getCacheMock(), 'set', $setArgs, $setCallCount);
        return $this->getDecorator($cache, $guard);
    }

    public function keyValueExpirationDataProvider()
    {
        $baseArr = array('key', 'value', 100);

        return array(
            array_merge($baseArr, array(true, 1)),
            array_merge($baseArr, array(false, 0)),
        );
    }
}