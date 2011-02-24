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
}
