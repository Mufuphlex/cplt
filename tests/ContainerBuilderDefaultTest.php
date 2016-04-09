<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\ContainerBuilderDefault;

class ContainerBuilderDefaultTest extends TestCase
{
    public function testBuild()
    {
        $tokenizer = $this->makeTokenizer();
        $sourceData = 'Some text is here';
        $builder = new ContainerBuilderDefault($tokenizer);
        $container = $builder->build($sourceData);
        $this->assertInstanceOf('\Mufuphlex\Cplt\ContainerInterface', $container);
    }

    /**
     * @dataProvider notStringDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testBuildFailsOnInvalidSourceData($sourceData)
    {
        $tokenizer = static::getMock('\Mufuphlex\Textonic\Tokenizer\TokenizerInterface');
        $builder = new ContainerBuilderDefault($tokenizer);
        $builder->build($sourceData);
    }

    private function makeTokenizer()
    {
        $tokenizer = static::getMock('\Mufuphlex\Textonic\Tokenizer\TokenizerInterface');
        $tokenizer
            ->expects(static::once())
            ->method('tokenize')
            ->willReturn(array(
                'Some',
                'text',
                'is',
                'here',
            ));

        return $tokenizer;
    }
}