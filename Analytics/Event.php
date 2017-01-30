<?php

namespace AntiMattr\GoogleBundle\Analytics;

/**
 * Class Event
 *
 * @link http://code.google.com/apis/analytics/docs/tracking/eventTrackerGuide.html
 */
class Event
{
    private $action;
    private $category;
    private $label;
    private $value;
    private $options;

    /**
     * @param string      $category Category
     * @param string      $action   Action
     * @param null|string $label    Label (optional)
     * @param null|string $value    Value (optional)
     * @param array       $options  Options
     */
    public function __construct($category, $action, $label = null, $value = null, array $options = [])
    {
        $this->action        = $action;
        $this->category      = $category;
        $this->label         = $label;
        $this->value         = $value;
        $this->options       = $options;
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
     * @return string|null $label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string|null $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
            'value' => $this->value,
            'options' => $this->options,
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
        if (isset($data['options'])) {
            $this->options = $data['options'];
        }
    }
}
