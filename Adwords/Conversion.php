<?php

namespace AntiMattr\GoogleBundle\Adwords;

class Conversion
{
    private $id;
    private $label;
    private $value;
    private $remarketing;

    public function __construct($id, $label, $value, $remarketing = false)
    {
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->remarketing = $remarketing;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getRemarketing()
    {
        return $this->remarketing;
    }
}
