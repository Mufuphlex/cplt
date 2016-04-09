<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\ContainerBuilderDefault;

class ContainerBuilderDefaultTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $tokenizer = static::getMock('\Mufuphlex\Textonic\Tokenizer\TokenizerInterface');
        $tokenizer
            ->expects(static::once())
            ->method('tokenize')
            ->willReturn(array());

        $sourceData = 'Some text is here';
        $builder = new ContainerBuilderDefault($tokenizer);
        $container = $builder->build($sourceData);
        $this->assertInstanceOf('\Mufuphlex\Cplt\ContainerInterface', $container);
    }
}