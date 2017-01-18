<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\AnalyticsHelper;

class AnalyticsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $analyticsHelper;

    public function __construct(AnalyticsHelper $analyticsHelper)
    {
        $this->analyticsHelper = $analyticsHelper;
    }

    public function getGlobals()
    {
        return array(
            'google_analytics' => $this->analyticsHelper
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'google_analytics';
    }
}
