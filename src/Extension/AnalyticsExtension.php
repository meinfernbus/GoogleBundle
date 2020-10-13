<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\AnalyticsHelper;
use Twig\Extension\AbstractExtension;

class AnalyticsExtension extends AbstractExtension implements \Twig_Extension_GlobalsInterface
{
    private $analyticsHelper;

    public function __construct(AnalyticsHelper $analyticsHelper)
    {
        $this->analyticsHelper = $analyticsHelper;
    }

    public function getGlobals()
    {
        return [
            'google_analytics' => $this->analyticsHelper,
        ];
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
