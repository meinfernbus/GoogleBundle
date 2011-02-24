<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\AdwordsHelper;

class AdwordsExtension extends \Twig_Extension
{
    private $adwordsHelper;

    public function __construct(AdwordsHelper $adwordsHelper)
    {
        $this->adwordsHelper = $adwordsHelper;
    }

    public function getGlobals()
    {
        return array(
            'google_adwords' => $this->adwordsHelper
        );
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
