<?php

namespace AntiMattr\GoogleBundle\Tests\Maps;

use AntiMattr\GoogleBundle\Maps\StaticMap;
use AntiMattr\GoogleBundle\Maps\Marker;

class StaticMapTest extends \PHPUnit_Framework_TestCase
{
	private $map;

	public function setUp()
	{
		parent::setup();
		$this->map = new StaticMap();
	}

	public function tearDown()
	{
		$this->map = null;
		parent::tearDown();
	}

	public function testConstructor()
	{
		$this->assertNull($this->map->getId());
		$this->assertFalse($this->map->hasMarkers());
		$this->assertFalse($this->map->hasMeta());
		$this->assertNull($this->map->getCenter());
		$this->assertFalse($this->map->getSensor());
		$this->assertNull($this->map->getSize());
		$this->assertNull($this->map->getType());
		$this->assertNull($this->map->getZoom());
	}

	public function testSetGetId()
	{
		$val = 'xxxxxx';
		$this->map->setId($val);
		$this->assertEquals($val, $this->map->getId());
	}

	public function testSetGetMarkers()
	{
		$marker = new Marker();
		$this->assertFalse($this->map->hasMarker($marker));
		$this->map->addMarker($marker);
		$this->assertTrue($this->map->hasMarker($marker));
		$this->map->removeMarker($marker);
		$this->assertFalse($this->map->hasMarker($marker));
	}

	public function testSetGetMeta()
	{
		$meta = array(1, 2, 3);
		$this->map->setMeta($meta);
		$this->assertEquals($meta, $this->map->getMeta());
	}

	public function testSetGetCenter()
	{
		$val = 'Brooklyn Bridge, New York';
		$this->map->setCenter($val);
		$this->assertEquals($val, $this->map->getCenter());
	}

	public function testSetGetSensor()
	{
		$val = false;
		$this->map->setSensor($val);
		$this->assertEquals($val, $this->map->getSensor());
	}

	public function testSetGetSize()
	{
		$val = '512x512';
		$this->map->setSize($val);
		$this->assertEquals($val, $this->map->getSize());
	}

	public function testSetGetType()
	{
		$val = StaticMap::TYPE_ROADMAP;
		$this->map->setType($val);
		$this->assertEquals($val, $this->map->getType());
	}

	public function testSetGetZoom()
	{
		$val = 14;
		$this->map->setZoom($val);
		$this->assertEquals($val, $this->map->getZoom());
	}
}
