<?php

namespace AntiMattr\GoogleBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Tag Manager Helper
 */
class TagManagerHelper extends Helper
{
    /**
     * @var array
     */
    private $containerIds;

    /**
     * Contructor
     *
     * @param array $containerIds
     */
    public function __construct($containerIds)
    {
        $this->containerIds = $containerIds;
    }

    /**
     * Gets container ids
     *
     * @return string
     */
    public function getContainerIds()
    {
        return $this->containerIds;
    }

    /**
     * Checks whether container ids are set
     *
     * @return boolean
     */
    public function hasContainerIds()
    {
        return !empty($this->containerIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'google_tag_manager';
    }
}
