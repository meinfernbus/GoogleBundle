<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Maps\MapInterface;
use Doctrine\Common\Collections\Collection;

class MapsManager
{
    private $config = array();
    private $maps = array();

    public function __construct(array $config = array())
    {
        $this->config = $config;
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
