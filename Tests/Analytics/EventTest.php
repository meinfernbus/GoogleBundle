<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    private $event;

    public function setUp()
    {
        parent::setup();
        $this->category = 'Test category';
        $this->action = 'Test action';
        $this->label = 'Test label';
        $this->value = 'Test value';
        $this->event = new Event($this->category, $this->action);
    }

    public function tearDown()
    {
        $this->event = null;
        $this->value = null;
        $this->label = null;
        $this->action = null;
        $this->category = null;
        parent::tearDown();
    }

    public function testConstructor()
    {
        $this->assertEquals($this->category, $this->event->getCategory());
        $this->assertEquals($this->action, $this->event->getAction());
        $this->assertNull($this->event->getLabel());
        $this->assertNull($this->event->getValue());

        $label = 'Test label';
        $value = 'Test value';
        $event = new Event($this->category, $this->action, $label, $value);
        $this->assertEquals($label, $event->getLabel());
        $this->assertEquals($value, $event->getValue());
    }
}
