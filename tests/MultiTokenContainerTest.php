<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\MultiTokenContainer;

class MultiTokenContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAndFindComplexToken()
    {
        $tokens = array(
            'the',
            'the peak',
            'pay the price',
        );

        $container = new MultiTokenContainer();

        foreach ($tokens as $token) {
            $container->addToken($token);
        }

        $result = $container->find('the');
        $this->assertEquals(
            array(
                'the',
                'the peak',
                'pay the price',
            ),
            $result
        );
    }
}