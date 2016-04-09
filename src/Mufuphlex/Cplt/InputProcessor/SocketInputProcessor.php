<?php

namespace Mufuphlex\Cplt\InputProcessor;

use Mufuphlex\Cplt\ContainerInterface;
use Mufuphlex\Sake\InputProcessorInterface;

/**
 * Class SocketInputProcessor
 * @package Mufuphlex\Cplt\InputProcessor
 */
class SocketInputProcessor implements InputProcessorInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * SocketInputProcessor constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        if ($this->container !== null) {
            throw new \LogicException('$container is already defined');
        }

        $this->container = $container;
        return $this;
    }

    /**
     * @param string $input
     * @return mixed
     */
    public function process($input)
    {
        $ts = microtime(true);
        $cache = 0;

        $result = array(
            'r' => null,
            'e' => null,
        );

        $normalInput = $this->normalizeInput($input);

        if (!$normalInput) {
            $result['e'] = 'Empty input';
        } else {
            $data = $this->container->find($normalInput);
            $result['r'] = $data;
        }

        $result['p'] = implode("\t", array(strtr($normalInput, array("\t" => ' ')), count($result['r']), (int)$cache, (microtime(true) - $ts), number_format(memory_get_usage(), null, null, ' ')));
        return json_encode($result);
    }

    /**
     * @param string $term
     * @return string mixed
     */
    private function normalizeInput($term)
    {
        $term = trim($term, " \t\r\n");
        $term = mb_strtolower($term, 'utf-8');
        return $term;
    }
}