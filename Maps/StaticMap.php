<?php

namespace AntiMattr\GoogleBundle\Maps;

class StaticMap extends AbstractMap
{
    const API_ENDPOINT = 'http://maps.google.com/maps/api/staticmap?';
    const TYPE_ROADMAP = 'roadmap';
    const CACHE_DIR = 'maps/';
    const SUFFIX = '.png';

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

    public function setKey($key)
    {
        $this->meta['key'] = (string) $key;
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
        $prefix  = static::API_ENDPOINT;
        $request = '';
        $cachePrefix = 'http://';
        if (!empty($_SERVER['SERVER_NAME'])) {
            $cachePrefix .= $_SERVER['SERVER_NAME'];
        }

        // Using router object would be better, but as this is a static class...
        // Checks according to php manual, regarding IIS
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $prefix = 'https' . substr($prefix, 4);
            $cachePrefix = 'https' . substr($cachePrefix, 4);
        }
        $queryData = array();
        if ($this->hasMeta()) {
            $queryData = $this->getMeta();
        }
        $queryData['sensor'] = ((bool)$this->getSensor()) ? 'true' : 'false';

        if (isset($queryData['key'])) {
            $apiKey = $queryData['key'];
            unset($queryData['key']);
        } else {
            $apiKey = '';
        }
        $request .= http_build_query($queryData);

        if ($this->hasMarkers()) {
            foreach ($this->getMarkers() as $marker) {
                $request .= '&markers=';
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

        $targetFile = str_replace(array('.',',','|','|',':','=','&'), '_', $request);
        $request .= '&key' . '=' . $apiKey;

        if (!is_dir($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir());
        }
        if (is_dir($this->getUploadRootDir())) {
            $targetFilePath = $this->getAbsolutePath($targetFile);
            if (!file_exists($targetFilePath) || (filemtime($targetFilePath) + 86400) < time()) {
                file_put_contents($targetFilePath, file_get_contents($prefix . $request));
                $request = $cachePrefix . $this->getWebPath($targetFile);
            } else {
                $request = $cachePrefix . $this->getWebPath($targetFile);
            }
        } else {
            $request = $prefix . $request;
        }
        $out = '<img id="'.$this->getId().'" src="'.$request.'" />';

        return $out;
    }

    protected function getAbsolutePath($filename)
    {
        return $this->getUploadRootDir() . $filename . self::SUFFIX;
    }

    protected function getWebPath($filename)
    {
        return '/' . self::CACHE_DIR . $filename . self::SUFFIX;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../../web/' . self::CACHE_DIR;
    }
}
