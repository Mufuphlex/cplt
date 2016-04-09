<?php

namespace Mufuphlex\Cplt;

/**
 * Interface ContainerBuilderInterface
 * @package Mufuphlex\Cplt
 */
interface ContainerBuilderInterface
{
    /**
     * @param mixed $sourceData
     * @return ContainerInterface
     */
    public function build($sourceData);
}