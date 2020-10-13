<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\AdwordsHelper;
use Twig\Extension\AbstractExtension;

class AdwordsExtension extends AbstractExtension implements \Twig_Extension_GlobalsInterface
{
    private $adwordsHelper;

    public function __construct(AdwordsHelper $adwordsHelper)
    {
        $this->adwordsHelper = $adwordsHelper;
    }

    public function getGlobals()
    {
        return [
            'google_adwords' => $this->adwordsHelper,
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'google_adwords';
    }
}
