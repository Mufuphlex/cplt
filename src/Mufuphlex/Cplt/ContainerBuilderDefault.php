<?php


namespace Mufuphlex\Cplt;

/**
 * Class ContainerBuilderDefault
 * @package Mufuphlex\Cplt
 */
class ContainerBuilderDefault implements ContainerBuilderInterface
{
    /** @var \Mufuphlex\Textonic\Tokenizer\TokenizerInterface */
    protected $tokenizer;

    /**
     * @param mixed $sourceData
     * @return ContainerInterface
     */
    public function build($sourceData)
    {
        if (!is_string($sourceData)) {
            throw new \InvalidArgumentException('$text must be a string');
        }

        $tokens = $this->tokenizer->tokenize($sourceData);
        $container = new Container();

        foreach ($tokens as $token) {
            $container->addToken($token);
        }

        return $container;
    }
}