<?php

namespace AntiMattr\GoogleBundle\Maps;

class StaticMap extends AbstractMap
{
    const API_ENDPOINT = 'http://maps.google.com/maps/api/staticmap?';
    const TYPE_ROADMAP = 'roadmap';
    const CACHE_DIR = '/maps';
    const SUFFIX = '.png';

    protected $height;
    protected $width;
    protected $imgAlt = '';
    protected $sensor = false;
    /**
     * @var string
     */
    protected $uploadDir = __DIR__ . '/../../../../web/' . self::CACHE_DIR;
    /**
     * @var string
     */
    protected $publicDir = self::CACHE_DIR;

    /**
     * Once file is generated, this will contain absolute path
     *
     * @var string|null
     */
    private $targetPath;

    protected static $typeChoices = [
        self::TYPE_ROADMAP => 'Road Map',
    ];

    public static function getTypeChoices()
    {
        return self::$typeChoices;
    }

    public static function isTypeValid($type)
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
            return $width . 'x' . $height;
        }
    }

    public function setType($type)
    {
        $type = (string) $type;
        if (false === $this->isTypeValid($type)) {
            throw new \InvalidArgumentException($type . ' is not a valid Static Map Type.');
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

    public function getImgAlt()
    {
        return $this->imgAlt;
    }

    public function setImgAlt($imgAlt)
    {
        $this->imgAlt = $imgAlt;
    }

    public function render()
    {
        $prefix = static::API_ENDPOINT;
        $request = '';
        $cachePrefix = 'http://';

        if (isset($this->meta['host'])) {
            $cachePrefix = $this->meta['host'];
        } else {
            if (!empty($_SERVER['HTTP_HOST'])) {
                $cachePrefix .= $_SERVER['HTTP_HOST'];
            }
        }

        // Using router object would be better, but as this is a static class...
        // Checks according to php manual, regarding IIS
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $prefix = 'https' . substr($prefix, 4);
            $cachePrefix = 'https' . substr($cachePrefix, 4);
        }
        $queryData = [];
        if ($this->hasMeta()) {
            $queryData = $this->getMeta();
        }
        $queryData['sensor'] = ((bool) $this->getSensor()) ? 'true' : 'false';

        $apiKey = '';
        if (isset($queryData['key'])) {
            $apiKey = $queryData['key'];
            unset($queryData['key']);
        }
        if (isset($queryData['host'])) {
            unset($queryData['host']);
        }
        $request .= http_build_query($queryData);

        if ($this->hasMarkers()) {
            foreach ($this->getMarkers() as $marker) {
                $request .= '&markers=';
                if ($marker->hasMeta()) {
                    foreach ($marker->getMeta() as $mkey => $mval) {
                        $request .= $mkey . ':' . $mval . '|';
                    }
                }
                if ($latitude = $marker->getLatitude()) {
                    $request .= $latitude;
                }
                if ($longitude = $marker->getLongitude()) {
                    $request .= ',' . $longitude;
                }
            }
        }

        $targetFile = str_replace(['.', ',', '|', '|', ':', '=', '&', '/', '?'], '_', $request);
        if (!empty($apiKey)) {
            $request .= '&key' . '=' . $apiKey;
        }

        if (!is_dir($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir());
        }
        if (is_dir($this->getUploadRootDir())) {
            $this->targetPath = $this->getAbsolutePath($targetFile);
            if (!file_exists($this->targetPath) || (filemtime($this->targetPath) + 86400) < time()) {
                file_put_contents($this->targetPath, file_get_contents($prefix . $request));
                $request = $cachePrefix . $this->getWebPath($targetFile);
            } else {
                $request = $cachePrefix . $this->getWebPath($targetFile);
            }
        } else {
            $request = $prefix . $request;
        }

        $out = sprintf(
            '<img id="%d" src="%s" %s />',
            $this->getId(),
            $request,
            $this->imgAlt == '' ? '' : 'alt="' . $this->imgAlt . '"'
        );

        return $out;
    }

    protected function getAbsolutePath($filename)
    {
        return $this->getUploadRootDir() . '/' . $filename . self::SUFFIX;
    }

    protected function getWebPath($filename): string
    {
        return $this->publicDir . '/' . $filename . self::SUFFIX;
    }

    protected function getUploadRootDir(): string
    {
        return $this->uploadDir;
    }

    public function setHost($host)
    {
        $this->meta['host'] = $host;
    }

    public function setUploadDir(string $uploadDir): void
    {
        $this->uploadDir = $uploadDir;
    }

    public function setPublicDir(string $publicDir): void
    {
        $this->publicDir = $publicDir;
    }

    /**
     * @return string|null absolute path to generated map file or null if it wasn't yet generated
     */
    public function getTargetPath(): ?string
    {
        return $this->targetPath;
    }
}
