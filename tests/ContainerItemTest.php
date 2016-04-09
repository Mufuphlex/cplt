<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\ContainerItem;

class ContainerItemTest extends TestCase
{
    public function testSetGetValue()
    {
        $item = new ContainerItem();
        $this->assertSame('', $item->getValue());
        $token = 'token';
        $item->setValue($token);
        $this->assertSame($token, $item->getValue());
    }

    /**
     * @dataProvider notStringDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetValueFails($value)
    {
        $item = new ContainerItem();
        $item->setValue($value);
    }

    public function testSetGetPopularity()
    {
        $item = new ContainerItem();
        $this->assertSame(0, $item->getPopularity());
        $popularity = rand(1,100);
        $item->setPopularity($popularity);
        $this->assertSame($popularity, $item->getPopularity());
    }

    /**
     * @dataProvider notIntegerDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetPopularityFails($value)
    {
        $item = new ContainerItem();
        $item->setPopularity($value);
    }

    public function testSetGetAncestors()
    {
        $item = new ContainerItem();
        $this->assertSame(array(), $item->getAncestors());
        $ancestors = array(
            't' => array('test'),
        );
        $item->setAncestors($ancestors);
        $this->assertSame($ancestors, $item->getAncestors());
    }

    /**
     * @dataProvider notArrayDataProvider
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessageRegExp /^Argument 1 passed to .+ must be (an( instance of)?|of the type) array,/
     */
    public function testSetAncestorsFailsOnNotArray($value)
    {
        $item = new ContainerItem();

        if (class_exists('\TypeError')) {
            try {
                $item->setAncestors($value);
            } catch (\TypeError $e) {
                throw new \PHPUnit_Framework_Error(
                    'Argument 1 passed to method must be an array, but not',
                    0,
                    $e->getFile(),
                    $e->getLine()
                );
            }
        } else {
            $item->setAncestors($value);
        }
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Can not redefine $ancestors
     */
    public function testSetAncestorsFailsOnRedefine()
    {
        $item = new ContainerItem();
        $item->setAncestors(array('t' => array()));
        $item->setAncestors(array());
    }
}