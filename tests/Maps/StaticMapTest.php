<?php

namespace AntiMattr\GoogleBundle\Tests\Maps;

use AntiMattr\GoogleBundle\Maps\Marker;
use AntiMattr\GoogleBundle\Maps\StaticMap;

class StaticMapTest extends \PHPUnit\Framework\TestCase
{
    private $map;

    public function setUp(): void
    {
        parent::setup();
        $this->map = new StaticMap();
    }

    public function tearDown(): void
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
        $meta = [1, 2, 3];
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

    public function testRender()
    {
        $mapsDir = __DIR__ . '/maps';
        $marker = new Marker();
        $marker->setMeta(['color' => 'green']);
        $marker->setLatitude(52.476050612437);
        $marker->setLongitude(13.364356141862);

        $this->map->setMeta([
            'key' => 'AIzaSyDdagXxC6br-y_Ih4nlxj3gxaiFKvObA_c',
            'host' => 'https://api.flix-tech.test',
            'size' => '640x350',
            'zoom' => 15,
            'maptype' => 'road',
                            ]);
        $this->map->setId('481');
        $this->map->setMarkers([$marker]);
        $this->map->setUploadDir($mapsDir);
        $this->map->setPublicDir('/maps');

        $out = '<img id="481" src="https://api.flix-tech.test/maps/size_640x350_zoom_15_maptype_road_sensor_false_markers_color_green_52_476050612437_13_364356141862.png"  />';

        $this->assertEquals($out, $this->map->render());
        $this->assertEquals(__DIR__ . '/maps/size_640x350_zoom_15_maptype_road_sensor_false_markers_color_green_52_476050612437_13_364356141862.png', $this->map->getTargetPath());

        unlink($this->map->getTargetPath());
        rmdir($mapsDir);
    }
}
