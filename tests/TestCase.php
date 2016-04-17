<?php

namespace Mufuphlex\Tests\Cplt;

/**
 * Class TestCase
 * @package Mufuphlex\Tests\Cplt
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function notStringDataProvider()
    {
        return array(
            array(1),
            array(1.0),
            array(array()),
            array(new \stdClass()),
            array(true),
            array(null),
            array(xml_parser_create()),
        );
    }

    /**
     * @return array
     */
    public function notIntegerDataProvider()
    {
        return array(
            array('text'),
            array(1.0),
            array(array()),
            array(new \stdClass()),
            array(true),
            array(null),
            array(xml_parser_create()),
        );
    }

    /**
     * @return array
     */
    public function notArrayDataProvider()
    {
        return array(
            array('text'),
            array(1.0),
            array(1),
            array(new \stdClass()),
            array(true),
            array(null),
            array(xml_parser_create()),
        );
    }
}