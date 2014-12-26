<?php

namespace AntiMattr\GoogleBundle\Analytics;

use AntiMattr\Common\Product\Product as CommonProduct;

/**
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#impression-data
 */
class Impression extends CommonProduct
{
    protected $action = 'detail';
    protected $brand;
    protected $category;
    protected $list;
    protected $position;
    protected $variant;

    /**
     * @param string
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

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
    public function setList($list)
    {
        $this->list = $list;
    }

    /**
     * @return string
     */
    public function getList()
    {
        return $this->list;
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
     * @param string
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }

    /**
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
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
            'action' => $this->action,
            'category' => $this->category,
            'variant' => $this->variant,
            'price' => $this->price,
            'list' => $this->list,
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
        if (isset($data['action'])) {
            $this->action = $data['action'];
        }
        if (isset($data['brand'])) {
            $this->brand = $data['brand'];
        }
        if (isset($data['category'])) {
            $this->category = $data['category'];
        }
        if (isset($data['variant'])) {
            $this->variant = $data['variant'];
        }
        if (isset($data['price'])) {
            $this->price = $data['price'];
        }
        if (isset($data['list'])) {
            $this->list = $data['list'];
        }
        if (isset($data['position'])) {
            $this->position = $data['position'];
        }
    }
}
