<?php

namespace Mufuphlex\Cplt;

/**
 * Class MultiTokenContainer
 * @package Mufuphlex\Cplt
 */
class MultiTokenContainer extends Container
{
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

        $tokens = explode(' ', $token);
        $tokensCount = count($tokens);

        if ($tokensCount === 1) {
            return parent::addToken($token, $namespace);
        }

        foreach ($tokens as $subToken) {
            $letters = str_split($subToken);

            if (count($letters) > 1) {
                array_pop($letters);
            }

            $destination = &$this->getFinalDestination($letters, $subToken, $namespace, true);

            if (isset($destination[$subToken]) && !$destination[$subToken]) {
                unset($destination[$subToken]);
            }

            if (!isset($destination[$token])) {
                $destination[$token] = 0;
            }

            $destination[$token] += 1/$tokensCount;
            arsort($destination);
        }

        return $this;
    }
}