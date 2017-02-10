<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Product;
use AntiMattr\TestCase\AntiMattrTestCase;

class ProductTest extends AntiMattrTestCase
{
    private $product;

    public function setUp()
    {
        $this->product = new Product();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('AntiMattr\Common\Product\Product', $this->product);
        $this->assertNull($this->product->getId());
        $this->assertNull($this->product->getSku());
        $this->assertNull($this->product->getTitle());
        $this->assertNull($this->product->getAction());
        $this->assertNull($this->product->getList());
        $this->assertNull($this->product->getBrand());
        $this->assertNull($this->product->getCategory());
        $this->assertNull($this->product->getPrice());
        $this->assertNull($this->product->getQuantity());
        $this->assertNull($this->product->getCoupon());
        $this->assertNull($this->product->getPosition());
    }

    public function testToArrayFromArray()
    {
        $product = new Product();
        $product->setId('id');
        $product->setSku('zzzz');
        $product->setTitle('Product X');
        $product->setAction('purchase');
        $product->setCategory('Category A');
        $product->setBrand('Brand A');
        $product->setCoupon('COUPONA');
        $product->setList('Search Results A');
        $product->setPrice(50.00);
        $product->setQuantity(1);
        $product->setPosition(1);
        $product->setVariant('Black');

        $toArray = $product->toArray();

        $product2 = new Product();
        $product2->fromArray($toArray);

        $this->assertEquals($product, $product2);
    }
}
