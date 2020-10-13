<?php

namespace AntiMattr\GoogleBundle\Tests;

use AntiMattr\GoogleBundle\Analytics\Event;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Transaction;

class AnalyticsWebTest extends IntegrationTestCase
{
    private $analytics;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->analytics = $this->getContainer()->get('google.analytics');
    }

    protected function tearDown(): void
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
        $this->assertCount(1, $this->analytics->getTrackers());
        $this->assertTrue($this->analytics->getAllowLinker('default'));
        $this->assertFalse($this->analytics->getAllowHash('default'));
        $this->assertTrue($this->analytics->getIncludeNamePrefix('default'));
        $this->assertTrue(0 < strlen($this->analytics->getTrackerName('default')));
    }

    public function testExpectedInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->analytics->getAllowLinker('not-a-tracker');
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
        return [
            [
                ['/page-a', '/page-b', '/page-c'],
                3,
            ],
            [
                ['/page-y', '/page-z'],
                2,
            ],
        ];
    }

    /**
     * @dataProvider provideEventsForQueue
     */
    public function testEnqueueEvent($eventData, $count)
    {
        foreach ($eventData as $data) {
            $event = new Event($data['category'], $data['action']);
            $this->analytics->enqueueEvent($event);
        }

        $this->assertTrue($this->analytics->hasEventQueue());
        $this->assertEquals($count, count($this->analytics->getEventQueue()));
    }

    public function provideEventsForQueue()
    {
        return [
            [
                [
                    ['category' => 'Category A', 'action' => 'Action A'],
                    ['category' => 'Category B', 'action' => 'Action B'],
                    ['category' => 'Category C', 'action' => 'Action C'],
                ],
                3,
            ],
            [
                [
                    ['category' => 'Category D', 'action' => 'Action D'],
                    ['category' => 'Category E', 'action' => 'Action E'],
                ],
                2,
            ],
        ];
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
        $transaction->setCity('NYC');
        $transaction->setState('NY');
        $transaction->setCountry('USA');
        $this->analytics->setTransaction($transaction);

        $this->assertTrue($this->analytics->isTransactionValid());
        $this->assertEquals($transaction, $this->analytics->getTransaction());

        $transaction = new Transaction();
        $transaction->setAffiliation('Store 777');
        $transaction->setTotal(100.00);
        $transaction->setTax(10.00);
        $transaction->setShipping(5.00);
        $transaction->setCity('NYC');
        $transaction->setState('NY');
        $transaction->setCountry('USA');
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
        $this->assertCount(2, $this->analytics->getItems());
    }

    public function testSetAllowAnchor()
    {
        $this->analytics->setAllowAnchor('default', false);
        $this->assertFalse($this->analytics->getAllowAnchor('default'));
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

    public function testSetIncludeNamePrefix()
    {
        $this->analytics->setIncludeNamePrefix('default', false);
        $this->assertFalse($this->analytics->getIncludeNamePrefix('default'));
    }

    public function testSetTrackerName()
    {
        $this->analytics->setTrackerName('default', 'a-different-name');
        $this->assertEquals('a-different-name', $this->analytics->getTrackerName('default'));
    }

    public function testSetSiteSpeedSampleRate()
    {
        $this->assertNull($this->analytics->getSiteSpeedSampleRate('default'));
        $this->analytics->setSiteSpeedSampleRate('default', '6');
        $this->assertEquals(6, $this->analytics->getSiteSpeedSampleRate('default'));
    }
}
