<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\TestCase\AntiMattrTestCase;

class ItemTest extends AntiMattrTestCase
{
    private $item;

    public function setUp()
    {
        $this->item = new Item();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('AntiMattr\Common\Product\Product', $this->item);
        $this->assertNull($this->item->getId());
        $this->assertNull($this->item->getTitle());
        $this->assertNull($this->item->getTitle());
        $this->assertNotNull($this->item->getAction());
        $this->assertNull($this->item->getBrand());
        $this->assertNull($this->item->getCategory());
        $this->assertNull($this->item->getOrderNumber());
        $this->assertNull($this->item->getPrice());
        $this->assertNull($this->item->getQuantity());
        $this->assertNull($this->item->getCoupon());
        $this->assertNull($this->item->getPosition());
    }

    public function testToArrayFromArray()
    {
        $item = new Item();
        $item->setId('id');
        $item->setSku('zzzz');
        $item->setTitle('Product X');
        $item->setCategory('Category A');
        $item->setBrand('Brand A');
        $item->setCoupon('COUPONA');
        $item->setOrderNumber('orderNumberA');
        $item->setPrice(50.00);
        $item->setQuantity(1);
        $item->setPosition(1);
        $item->setVariant('Black');

        $toArray = $item->toArray();

        $item2 = new Item();
        $item2->fromArray($toArray);

        $this->assertEquals($item, $item2);
    }
}
