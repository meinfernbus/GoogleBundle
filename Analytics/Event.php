<?php

namespace AntiMattr\GoogleBundle\Analytics;

/**
 * @link http://code.google.com/apis/analytics/docs/tracking/eventTrackerGuide.html
 */
class Event
{
    private $action;
    private $category;
    private $label;
    private $value;

    public function __contruct($category, $action, $label = null, $value = null)
    {
    	$this->action   = $action;
    	$this->category = $category;
    	$this->label    = $label;
    	$this->value    = $value;   
    }
   
    /**
     * @return string $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string $label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }
}
