<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container;

class ContainerSubmatchedTest extends TestCase
{
    public function testSubmatch()
    {
        $container = new Container\Submatched();
        $tokens = array(
            'Moscow',
            'cowboy',
        );

        foreach ($tokens as $token) {
            $container->addToken($token);
        }

        $result = $container->find('cow');
        static::assertCount(2, $result);
        static::assertSame($tokens[1], $result[0]);
        static::assertSame($tokens[0], $result[1]);
    }

    /**
     * @dataProvider notStringDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testAddTokenThrowsException($token)
    {
        $container = new Container\Submatched();
        $container->addToken($token);
    }
}