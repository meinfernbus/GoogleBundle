<?php

namespace AntiMattr\GoogleBundle\Analytics;

class CustomVariable
{
    private $index;
    private $name;
    private $value;
    private $scope = 1;

    public function __construct($index = null, $name = null, $value = null, $scope = 1)
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

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'index' => $this->index,
            'name' => $this->name,
            'value' => $this->value,
            'scope' => $this->scope
        );
    }

    /**
     * @param array
     */
    public function fromArray(array $data)
    {
        if (isset($data['index'])) {
            $this->index = $data['index'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }        
        if (isset($data['scope'])) {
            $this->scope = $data['scope'];
        }
    }       
}
