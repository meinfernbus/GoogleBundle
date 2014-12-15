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

    public function __construct($category = null, $action = null, $label = null, $value = null)
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

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'action' => $this->action,
            'category' => $this->category,
            'label' => $this->label,
            'value' => $this->value
        );
    }

    /**
     * @param array
     */
    public function fromArray(array $data)
    {
        if (isset($data['action'])) {
            $this->action = $data['action'];
        }        
        if (isset($data['category'])) {
            $this->category = $data['category'];
        }
        if (isset($data['label'])) {
            $this->label = $data['label'];
        }
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }
    }
}
