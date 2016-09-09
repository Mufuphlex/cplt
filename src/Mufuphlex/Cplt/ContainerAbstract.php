<?php

namespace Mufuphlex\Cplt;

use Mufuphlex\Logger;

abstract class ContainerAbstract implements ContainerInterface
{
    /** @var array */
    protected $data = array();

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

        return $this;
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function getData($namespace = '')
    {
        $namespace = $this->getCurrentNamespace($namespace);
        return (isset($this->data[$namespace]) ? $this->data[$namespace] : array());
    }

    /**
     * @param string $term
     * @param string $namespace
     * @return array
     */
    public function find($term, $namespace = '')
    {
        $letters = str_split($term);
        $lastLetter = (
        count($letters) > 1 ?
            array_pop($letters) :
            null
        );

        $termContainer = $this->getFinalDestination($letters, $term, $namespace);
        $termContainer = $this->filterTermContainer($termContainer, $term, $lastLetter);
        $result = $this->findInSubContainer($termContainer);

        if (isset($result[$term])) {
            $result[$term] = max($result) + 1;
        }

        arsort($result);
        return array_keys($result);
    }

    /**
     * @param array $subContainer
     * @return array
     */
    protected function &findInSubContainer(array $subContainer)
    {
        $result = array();

        foreach ($subContainer as $key => $value) {
            if (is_numeric($value)) {   // it's just a token and its popularity
                $result[$key] = $value;
                continue;
            }

            if (!is_array($value)) {    // it's ContainerItem
                $result[$value->getValue()] = $value->getPopularity();
                $value = $value->getAncestors();
            }

            $subResult = &$this->findInSubContainer($value);

            foreach ($subResult as $subResultKey => $subResultValue) {
                $result[$subResultKey] = $subResultValue;
            }
        }

        return $result;
    }

    /**
     * @param array $letters
     * @param string $token
     * @param $namespace
     * @param bool $createIfNotExists
     * @return array|ContainerItem
     * @TODO Split this method
     */
    protected function &getFinalDestination(array $letters, $token, $namespace, $createIfNotExists = false)
    {
        $destination = &$this->getCurrentNamespaceData($namespace);

        foreach ($letters as $letter) {
            if ($destination instanceof ContainerItem) {
                $destination = &$destination->getAncestors();
            }

            $letter = mb_strtolower($letter);

            if (!isset($destination[$letter])) {
                $destination[$letter] = array();
            }

            if (is_integer($destination[$letter])) {
                continue;
            }

            $destination = &$destination[$letter];

            if ($createIfNotExists && is_array($destination) &&
                (key($destination) == $letter) &&
                is_integer($popularity = current($destination))
            ) {
                $destination = new ContainerItem();
                $destination->setValue($letter);
                $destination->setPopularity($popularity);
                $destination = &$destination->getAncestors();
            }
        }

        if ($destination instanceof ContainerItem) {
            if (!$createIfNotExists) {
                $result = array($destination);
                return $result;
            }

            $destination = &$destination->getAncestors();
        }

        if ($createIfNotExists && !isset($destination[$token])) {
            $destination[$token] = 0;
        }

        return $destination;
    }

    /**
     * @param array $termContainer
     * @param string $term
     * @param string $lastLetter
     * @return array
     */
    protected function filterTermContainer(array $termContainer, $term, $lastLetter)
    {
        foreach ($termContainer as $key => $value) {
            if (!is_integer($value)) {
                if (is_array($value) && $lastLetter && ($key !== $lastLetter)) {
                    unset($termContainer[$key]);
                }

                continue;
            }

            if ($key !== $term) {
                unset($termContainer[$key]);
            }
        }

        return $termContainer;
    }

    /**
     * @param string $namespace
     * @return string
     */
    protected function getCurrentNamespace($namespace)
    {
        if (!$namespace) {
            return 0;
        }

        return $namespace;
    }

    /**
     * @param string $namespace
     * @return array
     */
    protected function &getCurrentNamespaceData($namespace)
    {
        $namespace = $this->getCurrentNamespace($namespace);

        if (!isset($this->data[$namespace])) {
            $this->data[$namespace] = array();
        }

        return $this->data[$namespace];
    }
}