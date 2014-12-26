<?php

namespace AntiMattr\GoogleBundle\Tests;

use AntiMattr\GoogleBundle\Analytics;
use AntiMattr\GoogleBundle\Analytics\Event;
use AntiMattr\GoogleBundle\Analytics\Impression;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Product;
use AntiMattr\GoogleBundle\Analytics\Transaction;
use AntiMattr\TestCase\AntiMattrTestCase;

class AnalyticsTest extends AntiMattrGoogleTestCase
{
    private $analytics;
    private $configuration;

    protected function setUp()
    {
        parent::setUp();

        $this->configuration = array(
            'default' => array(
                'name' => 'MyJavaScriptCompatibleVariableNameWithNoSpaces',
                'accountId' => 'xxxxxx',
                'domain' => '.example.com',
                'allowHash' => false,
                'allowLinker' => true,
                'trackPageLoadTime' => false
            )
        );
        $this->analytics = new Analytics($this->container, $this->configuration);
    }

    public function testConstructor()
    {
        $this->assertFalse($this->analytics->hasPageViewQueue());
        $this->assertFalse($this->analytics->hasCustomVariables());
        $this->assertFalse($this->analytics->hasItems());
        $this->assertFalse($this->analytics->hasProducts());
        $this->assertNull($this->analytics->getTransaction());
        $this->assertEquals(1, count($this->analytics->getTrackers()));
        $this->assertTrue($this->analytics->getAllowLinker('default'));
        $this->assertFalse($this->analytics->getAllowHash('default'));
        $this->assertTrue($this->analytics->getIncludeNamePrefix('default'));
        $this->assertTrue(0 < strlen($this->analytics->getTrackerName('default')));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExpectedInvalidArgumentException()
    {
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
        return array(
            array(
                array(
                    array('category' => 'Category A', 'action' => 'Action A'),
                    array('category' => 'Category B', 'action' => 'Action B'),
                    array('category' => 'Category C', 'action' => 'Action C')
                ),
                3
            ),
            array(
                array(
                    array('category' => 'Category D', 'action' => 'Action D'),
                    array('category' => 'Category E', 'action' => 'Action E'),
                ),
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

    public function testAddGetImpressions()
    {
        $impression = new Impression();
        $impression->setId('id');
        $impression->setSku('zzzz');
        $impression->setTitle('Product X');
        $impression->setAction('detail');
        $impression->setCategory('Category A');
        $impression->setBrand('Brand A');
        $impression->setList('Search Results A');
        $impression->setPrice(50.00);
        $impression->setPosition(1);
        $impression->setVariant('Black');

        $this->analytics->addImpression($impression);
        $this->assertTrue($this->analytics->hasImpression($impression));

        $impression = new Impression();
        $impression->setSku('jjjj');
        $impression->setTitle('Product J');
        $impression->setAction('detail');
        $impression->setCategory('Category B');
        $impression->setBrand('Brand B');
        $impression->setList('Search Results B');
        $impression->setPrice(25.00);
        $impression->setPosition(2);

        $this->analytics->addImpression($impression);
        $this->assertTrue($this->analytics->hasImpression($impression));

        $this->assertTrue($this->analytics->hasImpressions('detail'));
        $this->assertEquals(2, count($this->analytics->getImpressions('detail')));
    }

    public function testAddGetProducts()
    {
        $product = new Product();
        $product->setSku('zzzz');
        $product->setTitle('Product X');
        $product->setCategory('Category A');
        $product->setBrand('Brand A');
        $product->setCoupon('COUPONA');
        $product->setPrice(50.00);
        $product->setQuantity(1);
        $product->setPosition(1);
        $product->setVariant('Black');

        $this->analytics->addProduct($product);
        $this->assertTrue($this->analytics->hasProduct($product));

        $product = new Product();
        $product->setSku('jjjj');
        $product->setTitle('Product J');
        $product->setCategory('Category B');
        $product->setBrand('Brand B');
        $product->setCoupon('COUPONB');
        $product->setPrice(25.00);
        $product->setQuantity(2);
        $product->setPosition(2);

        $this->analytics->addProduct($product);
        $this->assertTrue($this->analytics->hasProduct($product));

        $this->assertTrue($this->analytics->hasProducts());
        $this->assertEquals(2, count($this->analytics->getProducts()));
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
