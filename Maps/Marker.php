<?php

namespace AntiMattr\GoogleBundle\Maps;

class Marker
{
    protected $latitude;
    protected $longitude;
    protected $meta = array();

    public function setColor($color)
    {
        $this->meta['color'] = (string) $color;
    }

    public function getColor()
    {
        if (array_key_exists('color', $this->meta)) {
            return $this->meta['color'];
        }
    }

    public function setLabel($label)
    {
        $this->meta['label'] = (string) $label;
    }

    public function getLabel()
    {
        if (array_key_exists('label', $this->meta)) {
            return $this->meta['label'];
        }
    }

    public function setLatitude($latitude)
    {
        $this->latitude = (float) $latitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = (float) $longitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function hasMeta()
    {
        return !empty($this->meta);
    }

    public function setMeta(array $meta = array())
    {
        $this->meta = $meta;
    }

    public function getMeta()
    {
        return $this->meta;
    }
}
