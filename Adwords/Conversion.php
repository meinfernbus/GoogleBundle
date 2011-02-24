<?php

namespace AntiMattr\GoogleBundle\Adwords;

class Conversion
{
    private $id;
    private $label;
    private $value;

    public function __construct($id, $label, $value)
    {
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
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
}
