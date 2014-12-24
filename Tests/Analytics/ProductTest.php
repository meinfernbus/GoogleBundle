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
        $this->assertNull($this->product->getTitle());
        $this->assertNull($this->product->getTitle());
        $this->assertNull($this->product->getCategory());
        $this->assertNull($this->product->getPrice());
        $this->assertNull($this->product->getQuantity());
        $this->assertNull($this->product->getPosition());
    }
}
