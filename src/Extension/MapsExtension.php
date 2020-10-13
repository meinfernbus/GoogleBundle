<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\MapsHelper;
use Twig\Extension\AbstractExtension;

class MapsExtension extends AbstractExtension implements \Twig_Extension_GlobalsInterface
{
    private $mapsHelper;

    public function __construct(MapsHelper $mapsHelper)
    {
        $this->mapsHelper = $mapsHelper;
    }

    public function getGlobals()
    {
        return [
            'google_maps' => $this->mapsHelper,
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'google_maps';
    }
}
