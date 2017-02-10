<?php

namespace AntiMattr\GoogleBundle\Analytics;

/**
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#measuring-transactions
 */
class Transaction
{
    protected $affiliation;
    protected $city;
    protected $country;
    protected $coupon;
    protected $orderNumber;
    protected $revenue;
    protected $shipping;
    protected $state;
    protected $tax;
    protected $total;

    public function setAffiliation($affiliation)
    {
        $this->affiliation = (string) $affiliation;
    }

    public function getAffiliation()
    {
        return $this->affiliation;
    }

    public function setCity($city)
    {
        $this->city = (string) $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCountry($country)
    {
        $this->country = (string) $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return string
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = (string) $orderNumber;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param float
     */
    public function setRevenue($revenue)
    {
        $this->revenue = (float) $revenue;
    }

    /**
     * @param float
     */
    public function getRevenue()
    {
        return ($this->revenue) ? $this->revenue : $this->total;
    }

    public function setShipping($shipping)
    {
        $this->shipping = (float) $shipping;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setState($state)
    {
        $this->state = (string) $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTax($tax)
    {
        $this->tax = (float) $tax;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setTotal($total)
    {
        $this->total = (float) $total;
    }

    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'affiliation' => $this->affiliation,
            'city' => $this->city,
            'country' => $this->country,
            'coupon' => $this->coupon,
            'orderNumber' => $this->orderNumber,
            'revenue' => $this->revenue,
            'shipping' => $this->shipping,
            'state' => $this->state,
            'tax' => $this->tax,
            'total' => $this->total
        );
    }

    /**
     * @param array
     */
    public function fromArray(array $data)
    {
        if (isset($data['affiliation'])) {
            $this->affiliation = $data['affiliation'];
        }
        if (isset($data['city'])) {
            $this->city = $data['city'];
        }
        if (isset($data['country'])) {
            $this->country = $data['country'];
        }
        if (isset($data['coupon'])) {
            $this->coupon = $data['coupon'];
        }
        if (isset($data['orderNumber'])) {
            $this->orderNumber = $data['orderNumber'];
        }
        if (isset($data['revenue'])) {
            $this->revenue = $data['revenue'];
        }
        if (isset($data['shipping'])) {
            $this->shipping = $data['shipping'];
        }
        if (isset($data['state'])) {
            $this->state = $data['state'];
        }
        if (isset($data['tax'])) {
            $this->tax = $data['tax'];
        }
        if (isset($data['total'])) {
            $this->total = $data['total'];
        }
    }
}
