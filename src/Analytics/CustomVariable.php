<?php

namespace AntiMattr\GoogleBundle\Analytics;

class CustomVariable
{
    private $index;
    private $name;
    private $value;
    private $scope = 1;

    public function __construct($index, $name, $value, $scope = 1)
    {
        $this->index = $index;
        $this->name = $name;
        $this->value = $value;
        $this->scope = $scope;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getScope()
    {
        return $this->scope;
    }
}
