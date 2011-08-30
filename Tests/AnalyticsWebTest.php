<?php

namespace AntiMattr\GoogleBundle\Tests;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AntiMattr\GoogleBundle\Analytics;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Transaction;

class AnalyticsWebTest extends WebTestCase
{
    private $analytics;
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static:createClient();
        $this->analytics = static::$kernel->getContainer()->get('google.analytics');
    }

    protected function tearDown()
    {
        $this->analytics = null;
        $this->client = null;
        parent::tearDown();
    }

    public function testConstructor()
    {
        $this->assertFalse($this->analytics->hasPageViewQueue());
        $this->assertFalse($this->analytics->hasCustomVariables());
        $this->assertFalse($this->analytics->hasItems());
        $this->assertNull($this->analytics->getTransaction());
        $this->assertEquals(1, count($this->analytics->getTrackers()));
        $this->assertTrue($this->analytics->getAllowLinker('default'));
        $this->assertFalse($this->analytics->getAllowHash('default'));
    }

    public function testSetGetCustomPageView()
    {
        $customPageView = '/profile/mattfitz';
        $this->assertFalse($this->analytics->hasCustomPageView());

        $this->analytics->setCustomPageView($customPageView);
        $this->assertTrue($this->analytics->hasCustomPageView());
        $this->assertEquals($customPageView, $this->analytics->getCustomPageView());

        $this->assertNull($this->analytics->getCustomPageView());
    }

    /**
     * @dataProvider providePageViewsForQueue
     */
    public function testEnqueuePageView($pageViews, $count)
    {
        foreach ($pageViews as $pageView) {
            $this->analytics->enqueuePageView($pageView);
        }

        $this->assertTrue($this->analytics->hasPageViewQueue());
        $this->assertEquals($count, count($this->analytics->getPageViewQueue()));
    }

    public function providePageViewsForQueue()
    {
        return array(
            array(
                array('/page-a', '/page-b', '/page-c'),
                3
            ),
            array(
                array('/page-y', '/page-z'),
                2
            )
        );
    }

    public function testSetGetTransaction()
    {
        $this->assertFalse($this->analytics->isTransactionValid());

        $transaction = new Transaction();
        $transaction->setOrderNumber('xxxx');
        $transaction->setAffiliation('Store 777');
        $transaction->setTotal(100.00);
        $transaction->setTax(10.00);
        $transaction->setShipping(5.00);
        $transaction->setCity("NYC");
        $transaction->setState("NY");
        $transaction->setCountry("USA");
        $this->analytics->setTransaction($transaction);

        $this->assertTrue($this->analytics->isTransactionValid());
        $this->assertEquals($transaction, $this->analytics->getTransaction());

        $transaction = new Transaction();
        $transaction->setAffiliation('Store 777');
        $transaction->setTotal(100.00);
        $transaction->setTax(10.00);
        $transaction->setShipping(5.00);
        $transaction->setCity("NYC");
        $transaction->setState("NY");
        $transaction->setCountry("USA");
        $this->analytics->setTransaction($transaction);
        $this->assertFalse($this->analytics->isTransactionValid());
    }

    public function testAddGetItems()
    {
        $item = new Item();
        $item->setOrderNumber('xxxx');
        $item->setSku('zzzz');
        $item->setName('Product X');
        $item->setCategory('Category A');
        $item->setPrice(50.00);
        $item->setQuantity(1);

        $this->analytics->addItem($item);
        $this->assertTrue($this->analytics->hasItem($item));

        $item = new Item();
        $item->setOrderNumber('bbbb');
        $item->setSku('jjjj');
        $item->setName('Product Y');
        $item->setCategory('Category B');
        $item->setPrice(25.00);
        $item->setQuantity(2);

        $this->analytics->addItem($item);
        $this->assertTrue($this->analytics->hasItem($item));

        $this->assertTrue($this->analytics->hasItems());
        $this->assertEquals(2, count($this->analytics->getItems()));
    }

    public function testSetAllowHash()
    {
        $this->analytics->setAllowHash('default', true);
        $this->assertTrue($this->analytics->getAllowHash('default'));
    }

    public function testSetAllowLinker()
    {
        $this->analytics->setAllowLinker('default', false);
        $this->assertFalse($this->analytics->getAllowLinker('default'));
    }

    public function testSetTrackPageLoadTime()
    {
        $this->analytics->setTrackPageLoadTime('default', true);
        $this->assertTrue($this->analytics->getTrackPageLoadTime('default'));
    }
}
