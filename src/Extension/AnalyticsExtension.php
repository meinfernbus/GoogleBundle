<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\AnalyticsHelper;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AnalyticsExtension extends AbstractExtension implements GlobalsInterface
{
    private $analyticsHelper;

    public function __construct(AnalyticsHelper $analyticsHelper)
    {
        $this->analyticsHelper = $analyticsHelper;
    }

    public function getGlobals(): array
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
    public function getName(): string
    {
        return 'google_analytics';
    }
}
