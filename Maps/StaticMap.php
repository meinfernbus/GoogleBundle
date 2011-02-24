<?php

namespace AntiMattr\GoogleBundle\Maps;

class StaticMap extends AbstractMap
{
    const API_ENDPOINT = 'http://maps.google.com/maps/api/staticmap?';
    const TYPE_ROADMAP = 'roadmap';

    protected $height;
    protected $width;
    protected $sensor = false;

    static protected $typeChoices = array(
        self::TYPE_ROADMAP => 'Road Map',
    );

    static public function getTypeChoices()
    {
        return self::$typeChoices;
    }

    static public function isTypeValid($type)
    {
        return array_key_exists($type, static::$typeChoices);
    }

    public function setCenter($center)
    {
        $this->meta['center'] = (string) $center;
    }

    public function getCenter()
    {
        if (array_key_exists('center', $this->meta)) {
            return $this->meta['center'];
        }
    }

    public function setHeight($height)
    {
        $this->height = (int) $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setSensor($sensor)
    {
        $this->sensor = (bool) $sensor;
    }

    public function getSensor()
    {
        return $this->sensor;
    }

    public function setSize($size)
    {
        $arr = explode('x', $size);
        if (isset($arr[0])) {
            $this->width = $arr[0];
        }
        if (isset($arr[1])) {
            $this->height = $arr[1];
        }
        $this->meta['size'] = $size;
    }

    public function getSize()
    {
        if (array_key_exists('size', $this->meta)) {
            return $this->meta['size'];
        }
        if (($height = $this->getHeight()) && ($width = $this->getWidth())) {
            return $width.'x'.$height;
        }
    }

    public function setType($type)
    {
        $type = (string) $type;
        if (FALSE === $this->isTypeValid($type)) {
            throw new \InvalidArgumentException($type.' is not a valid Static Map Type.');
        }
        $this->meta['type'] = $type;
    }

    public function getType()
    {
        if (array_key_exists('type', $this->meta)) {
            return $this->meta['type'];
        }
    }

    public function setWidth($width)
    {
        $this->width = (int) $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setZoom($zoom)
    {
        $this->meta['zoom'] = (int) $zoom;
    }

    public function getZoom()
    {
        if (array_key_exists('zoom', $this->meta)) {
            return $this->meta['zoom'];
        }
    }

    public function render()
    {
        $request  = static::API_ENDPOINT;
        if ($this->hasMeta()) {
            foreach ($this->getMeta() as $key => $val) {
                $request .= $key.'='.$val.'&';
            }
        }
        if ($this->getSensor()) {
            $request .= 'sensor=true&';
        } else {
            $request .= 'sensor=false&';
        }
        if ($this->hasMarkers()) {
            foreach ($this->getMarkers() as $marker) {
                $request .= 'markers=';
                if ($marker->hasMeta()) {
                    foreach ($marker->getMeta() as $mkey => $mval) {
                        $request .= $mkey.':'.$mval.'|';
                    }
                }
                if ($latitude = $marker->getLatitude()) {
                    $request .= $latitude;
                }
                if ($longitude = $marker->getLongitude()) {
                    $request .= ','.$longitude;
                }
            }	
        }
        $request = rtrim($request, "& ");
        $out = '<img id="'.$this->getId().'" src="'.$request.'" />';
        return $out;
    }
}
