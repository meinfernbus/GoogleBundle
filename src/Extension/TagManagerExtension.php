<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\TagManagerHelper;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Tag Manager Extension
 */
class TagManagerExtension extends AbstractExtension implements GlobalsInterface
{
    private $tagManagerHelper;

    /**
     * Constructor
     */
    public function __construct(TagManagerHelper $tagManagerHelper)
    {
        $this->tagManagerHelper = $tagManagerHelper;
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        return [
            'google_tag_manager' => $this->tagManagerHelper,
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return 'google_tag_manager';
    }
}
