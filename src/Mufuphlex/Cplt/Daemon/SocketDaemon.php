<?php

namespace Mufuphlex\Cplt\Daemon;
use Mufuphlex\Sake\SocketListenerInterface;

/**
 * Class SocketDaemon
 * @package Mufuphlex\Cplt
 */
class SocketDaemon implements DaemonInterface
{
    /** @var SocketListenerInterface */
    private $socketListener;

    /**
     * SocketDaemon constructor.
     * @param SocketListenerInterface $socketListener
     */
    public function __construct(SocketListenerInterface $socketListener)
    {
        $this->socketListener = $socketListener;
    }

    /**
     * @return bool
     */
    public function run()
    {
        return $this->socketListener->run();
    }
}