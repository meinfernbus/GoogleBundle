<?php

namespace AntiMattr\GoogleBundle\Analytics;

class Transaction
{
    private $affiliation;
    private $city;
    private $country;
    private $orderNumber;
    private $shipping;
    private $state;
    private $tax;
    private $total;

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

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = (string) $orderNumber;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
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
            'orderNumber' => $this->orderNumber,
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
        if (isset($data['orderNumber'])) {
            $this->orderNumber = $data['orderNumber'];
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
