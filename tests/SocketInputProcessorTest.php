<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\InputProcessor\SocketInputProcessor;

class SocketInputProcessorTest extends \PHPUnit_Framework_TestCase
{
     /**
     * @expectedException \LogicException
     * @expectedExceptionMessage $container is already defined
     */
    public function testSetContainerFailsOnRedefine()
    {
        $container = static::createMock('\Mufuphlex\Cplt\ContainerInterface');
        $inputProcessor = new SocketInputProcessor($container);
        $inputProcessor->setContainer($container);
    }

    public function testSetContainerDoesNotFail()
    {
        $container = static::createMock('\Mufuphlex\Cplt\ContainerInterface');
        $inputProcessor = new SocketInputProcessor();
        $inputProcessor->setContainer($container);
        static::assertJson($inputProcessor->process('text'));
    }

    public function testProcess()
    {
        $input = 't';
        $container = static::createMock('\Mufuphlex\Cplt\ContainerInterface');
        $container
            ->expects(static::once())
            ->method('find')
            ->with($input)
            ->willReturn(array('text'));

        $inputProcessor = new SocketInputProcessor($container);
        $result = $inputProcessor->process($input);

        static::assertJson($result);
        $array = json_decode($result, true);
        static::assertArrayKeys($array);
        static::assertEquals(
            array(
                'text',
            ),
            $array['r']
        );
        static::assertNull($array['e']);
    }

    public function testProcessWithError()
    {
        $input = ' ';
        $container = static::createMock('\Mufuphlex\Cplt\ContainerInterface');
        $container
            ->expects(static::never())
            ->method('find');

        $inputProcessor = new SocketInputProcessor($container);
        $result = $inputProcessor->process($input);

        static::assertJson($result);
        $array = json_decode($result, true);
        static::assertArrayKeys($array);
        static::assertEquals('Empty input', $array['e']);
        static::assertNull($array['r']);
    }

    public function testProcessWithNameSpace()
    {
        $input = 't';
        $namespace = 'animals';
        $token = 'tiger';

        $container = static::createMock('\Mufuphlex\Cplt\ContainerInterface');
        $container
            ->expects(static::once())
            ->method('find')
            ->with($input, $namespace)
            ->willReturn(array($token));

        $inputProcessor = new SocketInputProcessor($container);
        $result = $inputProcessor->process(implode('|', array($input, $namespace)));

        static::assertJson($result);
        $array = json_decode($result, true);
        static::assertArrayKeys($array);
        static::assertEquals(
            array(
                $token,
            ),
            $array['r']
        );
        static::assertNull($array['e']);
    }

    /**
     * @param array $array
     */
    private function assertArrayKeys(array $array)
    {
        static::assertArrayHasKey('r', $array);
        static::assertArrayHasKey('e', $array);
        static::assertArrayHasKey('p', $array);
    }
}