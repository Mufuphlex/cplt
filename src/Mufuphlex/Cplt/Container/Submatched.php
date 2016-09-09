<?php

namespace Mufuphlex\Cplt\Container;

use Mufuphlex\Cplt\ContainerAbstract;

/**
 * Class Submatched
 * @package Mufuphlex\Cplt\Container
 */
final class Submatched extends ContainerAbstract
{
    const SUBSTRING_WEIGHT_DEFAULT = 0.1;

    /**
     * @param string $token
     * @param string $namespace
     * @return $this
     */
    public function addToken($token, $namespace = '')
    {
         if (!is_string($token)) {
            throw new \InvalidArgumentException('$token must be a string, '.gettype($token).' given');
        }

        $letters = str_split($token);

        if (count($letters) > 1) {
            array_pop($letters);
        }

        $destination = &$this->getFinalDestination($letters, $token, $namespace, true);
        $destination[$token]++;
        arsort($destination);

        $this->addSubTokens($token, $letters, $namespace);

        return $this;
    }

    /**
     * @param string $originalToken
     * @param array $originalLetters
     * @param string $namespace
     * @return void
     */
    private function addSubTokens($originalToken, array $originalLetters, $namespace)
    {
        $originalLength = mb_strlen($originalToken);

        while (count($originalLetters) > 0) {
            array_shift($originalLetters);
            $this->addSubToken(
                $originalLetters,
                $originalToken,
                $namespace,
                count($originalLetters) / $originalLength * static::SUBSTRING_WEIGHT_DEFAULT
            );
        }
    }

    /**
     * @param array $letters
     * @param string $token
     * @param string $namespace
     * @param float $weight
     * @return void
     */
    private function addSubToken(array $letters, $token, $namespace, $weight)
    {
        $destination = &$this->getFinalDestination($letters, $token, $namespace, true);
        $destination[$token] += $weight;
        arsort($destination);
    }
}