<?php

namespace Mufuphlex\Cplt;

use Mufuphlex\Textonic\Tokenizer\TokenizerInterface;

/**
 * Class ContainerBuilderDefault
 * @package Mufuphlex\Cplt
 */
class ContainerBuilderDefault implements ContainerBuilderInterface
{
    /** @var TokenizerInterface */
    protected $tokenizer;

    /**
     * ContainerBuilderDefault constructor.
     * @param TokenizerInterface $tokenizer
     */
    public function __construct(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

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