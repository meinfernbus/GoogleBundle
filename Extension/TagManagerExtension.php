<?php

namespace AntiMattr\GoogleBundle\Extension;

use AntiMattr\GoogleBundle\Helper\TagManagerHelper;

/**
 * Tag Manager Extension
 */
class TagManagerExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $tagManagerHelper;

    /**
     * Constructor
     *
     * @param TagManagerHelper $tagManagerHelper
     */
    public function __construct(TagManagerHelper $tagManagerHelper)
    {
        $this->tagManagerHelper = $tagManagerHelper;
    }

    /**
     * @return array
     */
    public function getGlobals()
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
    public function getName()
    {
        return 'google_tag_manager';
    }
}
