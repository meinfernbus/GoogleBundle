<?php

namespace AntiMattr\GoogleBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Tag Manager Helper
 */
class TagManagerHelper extends Helper
{
    /**
     * @var string
     */
    private $containerId;

    /**
     * Contructor
     *
     * @param string $containerId
     */
    public function __construct($containerId)
    {
        $this->containerId = $containerId;
    }

    /**
     * Gets container id
     *
     * @return string
     */
    public function getContainerId()
    {
        return $this->containerId;
    }

    /**
     * Checks whether container id is set
     *
     * @return boolean
     */
    public function hasContainerId()
    {
        return !empty($this->containerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'google_tag_manager';
    }
}
