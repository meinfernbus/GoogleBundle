<?php

declare(strict_types=1);

namespace AntiMattr\GoogleBundle\Tests;

use AntiMattr\GoogleBundle\Maps\Marker;
use AntiMattr\GoogleBundle\MapsManager;

class MapsManagerTest extends IntegrationTestCase
{
    /**
     * @var MapsManager
     */
    private $mapsManager;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->mapsManager = $this->getContainer()->get('google.maps');
    }

    public function testStaticMapCreation(): void
    {
        if ($this->mapsManager->getKey() === 'default') {
            $this->markTestSkipped('This test requires correct API key');
        }

        //Arrange
        $map = $this->mapsManager->createStaticMap();
        $marker = new Marker();
        $marker->setLatitude('52.52437');
        $marker->setLongitude('13.41053');

        $map->addMarker($marker);
        $map->setSize('640x450');
        $map->setZoom(15);
        $map->setMeta(
            array_merge(
                $map->getMeta(),
                ['maptype' => 'road']
            )
        );

        //Act
        $map->render();

        //Assert
        $this->assertFileIsReadable($map->getTargetPath());
    }
}
