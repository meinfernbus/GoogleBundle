<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Maps\MapInterface;
use Doctrine\Common\Collections\Collection;

class MapsManager
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var MapInterface[]
     */
    private $maps = [];
    /**
     * @var string
     */
    private $uploadDir;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->uploadDir = $config['uploadDir'];
    }

    public function setKey($key)
    {
        $this->config['key'] = $key;
    }

    public function getKey()
    {
        if (array_key_exists('key', $this->config)) {
            return $this->config['key'];
        }
    }

    public function hasMaps()
    {
        if (!empty($this->maps)) {
            return true;
        }

        return false;
    }

    public function hasMap(MapInterface $map)
    {
        if ($this->maps instanceof Collection) {
            return $this->maps->contains($map);
        } else {
            return in_array($map, $this->maps, true);
        }
    }

    public function addMap(MapInterface $map)
    {
        $this->maps[] = $map;
    }

    public function createStaticMap()
    {
        $map = new Maps\StaticMap();
        $map->setUploadDir($this->config['uploadDir']);
        $map->setPublicDir($this->config['publicDir']);

        if (isset($this->config['key'])) {
            $map->setKey($this->config['key']);
        }

        if (isset($this->config['host'])) {
            $map->setHost($this->config['host']);
        }

        return $map;
    }

    public function removeMap(MapInterface $map)
    {
        if (!$this->hasMap($map)) {
            return null;
        }
        if ($this->maps instanceof Collection) {
            return $this->maps->removeElement($map);
        } else {
            unset($this->maps[array_search($map, $this->maps, true)]);

            return $map;
        }
    }

    public function setMaps($maps)
    {
        $this->maps = $maps;
    }

    public function getMaps()
    {
        return $this->maps;
    }

    public function getMapById($id)
    {
        foreach ($this->maps as $map) {
            if ($id == $map->getId()) {
                return $map;
            }
        }
    }
}
