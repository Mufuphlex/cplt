<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Daemon\SocketDaemon;

class SocketDaemonTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $socketListener = static::getMock('\Mufuphlex\Sake\SocketListenerInterface');
        $socketListener
            ->expects(static::once())
            ->method('run')
            ->willReturn(true);

        $socketDaemon = new SocketDaemon($socketListener);
        $this->assertTrue($socketDaemon->run());
    }
}