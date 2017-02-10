<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Impression;
use AntiMattr\TestCase\AntiMattrTestCase;

class ImpressionTest extends AntiMattrTestCase
{
    private $impression;

    public function setUp()
    {
        $this->impression = new Impression();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('AntiMattr\Common\Product\Product', $this->impression);
        $this->assertNull($this->impression->getId());
        $this->assertNull($this->impression->getSku());
        $this->assertNull($this->impression->getTitle());
        $this->assertNotNull($this->impression->getAction());
        $this->assertNull($this->impression->getBrand());
        $this->assertNull($this->impression->getCategory());
        $this->assertNull($this->impression->getPrice());
        $this->assertNull($this->impression->getList());
        $this->assertNull($this->impression->getPosition());
    }

    public function testToArrayFromArray()
    {
        $impression = new Impression();
        $impression->setId('id');
        $impression->setSku('zzzz');
        $impression->setTitle('Product X');
        $impression->setCategory('Category A');
        $impression->setAction('detail');
        $impression->setBrand('Brand A');
        $impression->setList('Search Results A');
        $impression->setPrice(50.00);
        $impression->setPosition(1);
        $impression->setVariant('Black');

        $toArray = $impression->toArray();

        $impression2 = new Impression();
        $impression2->fromArray($toArray);

        $this->assertEquals($impression, $impression2);
    }
}
