<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeDemo()
    {
        $this->assertInstanceOf(
            '\Mufuphlex\Cplt\Daemon\DaemonInterface',
            Factory::makeDemo('', 80)
        );
    }

    public function testMakeDemoCached()
    {
        $this->assertInstanceOf(
            '\Mufuphlex\Cplt\Daemon\DaemonInterface',
            Factory::makeDemo('', 80)
        );
    }
}