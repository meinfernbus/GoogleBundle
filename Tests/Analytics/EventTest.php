<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Event;
use AntiMattr\TestCase\AntiMattrTestCase;

class EventTest extends AntiMattrTestCase
{
    private $event;

    public function setUp()
    {
        $this->category = 'Test category';
        $this->action = 'Test action';
        $this->label = 'Test label';
        $this->value = 'Test value';
        $this->event = new Event($this->category, $this->action);
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
