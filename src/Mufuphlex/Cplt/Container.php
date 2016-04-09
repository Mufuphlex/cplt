<?php


namespace Mufuphlex\Cplt;

/**
 * Class Container
 * @package Mufuphlex\Cplt
 */
class Container implements ContainerInterface
{
    /** @var array */
    protected $data = array();

    /**
     * @param string $token
     * @return $this
     */
    public function addToken($token)
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('$token must be a string, '.gettype($token).' given');
        }

        $letters = str_split($token);

        if (count($letters) > 1) {
            array_pop($letters);
        }

        $destination = &$this->getFinalDestination($letters, $token, true);
        $destination[$token]++;
        arsort($destination);

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $term
     * @return array
     */
    public function find($term)
    {
        $letters = str_split($term);
        $lastLetter = (
        count($letters) > 1 ?
            array_pop($letters) :
            null
        );

        $termContainer = $this->getFinalDestination($letters, $term);
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
    private function &findInSubContainer(array $subContainer)
    {
        $result = array();

        foreach ($subContainer as $key => $value) {
            if (is_integer($value)) {   // it's just a token and its popularity
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
     * @param bool $createIfNotExists
     * @return array|ContainerItem
     * @TODO Split this method
     */
    private function &getFinalDestination(array $letters, $token, $createIfNotExists = false)
    {
        $destination = &$this->data;

        foreach ($letters as $letter) {
            if ($destination instanceof ContainerItem) {
                $destination = &$destination->getAncestors();
            }

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
    private function filterTermContainer(array $termContainer, $term, $lastLetter)
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
}