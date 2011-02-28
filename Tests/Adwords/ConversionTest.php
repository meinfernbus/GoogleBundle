<?php

namespace AntiMattr\GoogleBundle\Tests\Adwords;

use AntiMattr\GoogleBundle\Adwords\Conversion;

class ConversionTest extends \PHPUnit_Framework_TestCase
{
    private $conversion;

    public function setUp()
    {
        parent::setup();
        $this->id = 'xxxxxx';
        $this->label = 'my_label';
        $this->value = 100;
        $this->conversion = new Conversion($this->id, $this->label, $this->value);
    }

    public function tearDown()
    {
        $this->id = null;
        $this->label = null;
        $this->value = null;
        $this->conversion = null;
        parent::tearDown();
    }

    public function testConstructor()
    {
        $this->assertEquals($this->id, $this->conversion->getId());
        $this->assertEquals($this->label, $this->conversion->getLabel());
        $this->assertEquals($this->value, $this->conversion->getValue());
    }
}
