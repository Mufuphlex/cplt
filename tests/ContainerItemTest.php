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
}