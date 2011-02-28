<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    private $item;

    public function setUp()
    {
        parent::setup();
        $this->item = new Item();
    }

    public function tearDown()
    {
        $this->item = null;
        parent::tearDown();
    }

    public function testConstructor()
    {
        $this->assertNull($this->item->getCategory());
        $this->assertNull($this->item->getName());
        $this->assertNull($this->item->getOrderNumber());
        $this->assertNull($this->item->getPrice());
        $this->assertNull($this->item->getQuantity());
        $this->assertNull($this->item->getSku());
    }

    public function testSetGetCategory()
    {
        $val = "category";
        $this->item->setCategory($val);
        $this->assertEquals($val, $this->item->getCategory());
    }

    public function testSetGetName()
    {
        $val = "name";
        $this->item->setName($val);
        $this->assertEquals($val, $this->item->getName());
    }

    public function testSetGetOrderNumber()
    {
        $val = "xxxxxx";
        $this->item->setOrderNumber($val);
        $this->assertEquals($val, $this->item->getOrderNumber());
    }

    public function testSetGetPrice()
    {
        $val = 99.99;
        $this->item->setPrice($val);
        $this->assertEquals($val, $this->item->getPrice());
    }

    public function testSetGetQuantity()
    {
        $val = 7;
        $this->item->setQuantity($val);
        $this->assertEquals($val, $this->item->getQuantity());
    }

    public function testSetGetSku()
    {
        $val = '8888';
        $this->item->setSku($val);
        $this->assertEquals($val, $this->item->getSku());
    }
}
