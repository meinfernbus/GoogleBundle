<?php

namespace AntiMattr\GoogleBundle\Analytics;

class Item
{
    private $category;
    private $name;
    private $orderNumber;
    private $price;
    private $quantity;
    private $sku;

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setName($name)
    {
        $this->name = (string) $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = (string) $orderNumber;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function setPrice($price)
    {
        $this->price = (float) $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setSku($sku)
    {
        $this->sku = (string) $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'category' => $this->category,
            'name' => $this->name,
            'orderNumber' => $this->orderNumber,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sku' => $this->sku
        );
    }

    /**
     * @param array
     */
    public function fromArray(array $data)
    {
        if (isset($data['category'])) {
            $this->category = $data['category'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['orderNumber'])) {
            $this->orderNumber = $data['orderNumber'];
        }        
        if (isset($data['price'])) {
            $this->price = $data['price'];
        }
        if (isset($data['quantity'])) {
            $this->quantity = $data['quantity'];
        }        
        if (isset($data['sku'])) {
            $this->sku = $data['sku'];
        }  
    }      
}
