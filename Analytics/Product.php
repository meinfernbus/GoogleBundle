<?php

namespace AntiMattr\GoogleBundle\Analytics;

use AntiMattr\Common\Product\Product as CommonProduct;

/**
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#product-data
 */
class Product extends CommonProduct
{
    protected $brand;
    protected $category;
    protected $position;

    /**
     * @return string
     */
    public function getId()
    {
        return ($this->id) ? $this->id : $this->sku;
    }

    /**
     * @param string
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'sku' => $this->sku,
            'name' => $this->title,
            'brand' => $this->brand,
            'category' => $this->category,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'position' => $this->position
        );
    }

    /**
     * @param array
     */
    public function fromArray(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['sku'])) {
            $this->sku = $data['sku'];
        }
        if (isset($data['name'])) {
            $this->title = $data['name'];
        }
        if (isset($data['category'])) {
            $this->category = $data['category'];
        }
        if (isset($data['price'])) {
            $this->price = $data['price'];
        }
        if (isset($data['quantity'])) {
            $this->quantity = $data['quantity'];
        }
        if (isset($data['position'])) {
            $this->position = $data['position'];
        }
    }
}
