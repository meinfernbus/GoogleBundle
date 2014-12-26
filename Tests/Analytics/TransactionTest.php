<?php

namespace AntiMattr\GoogleBundle\Tests\Analytics;

use AntiMattr\GoogleBundle\Analytics\Transaction;
use AntiMattr\TestCase\AntiMattrTestCase;

class TransactionTest extends AntiMattrTestCase
{
    protected $transaction;

    public function setUp()
    {
        $this->transaction = new Transaction();
    }

    public function testConstructor()
    {
        $this->assertNull($this->transaction->getAffiliation());
        $this->assertNull($this->transaction->getCity());
        $this->assertNull($this->transaction->getCountry());
        $this->assertNull($this->transaction->getOrderNumber());
        $this->assertNull($this->transaction->getRevenue());
        $this->assertNull($this->transaction->getShipping());
        $this->assertNull($this->transaction->getState());
        $this->assertNull($this->transaction->getTax());
        $this->assertNull($this->transaction->getTotal());
    }

    public function testToArrayFromArray()
    {
        $transaction = new Transaction();
        $transaction->setOrderNumber('xxxx');
        $transaction->setAffiliation('Store 777');
        $transaction->setRevenue(85.00);
        $transaction->setTotal(100.00);
        $transaction->setTax(10.00);
        $transaction->setShipping(5.00);
        $transaction->setCity("NYC");
        $transaction->setState("NY");
        $transaction->setCountry("USA");

        $toArray = $transaction->toArray();

        $transaction2 = new Transaction();
        $transaction2->fromArray($toArray);

        $this->assertEquals($transaction, $transaction2);
    }
}
