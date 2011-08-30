<?php

namespace AntiMattr\GoogleBundle\Tests;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AntiMattr\GoogleBundle\Adwords;
use AntiMattr\GoogleBundle\Adwords\Conversion;

class AdwordsWebTest extends WebTestCase
{
    private $adwords;
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->adwords = static::$kernel->getContainer()->get('google.adwords');
    }

    protected function tearDown()
    {
        $this->adwords = null;
        $this->client = null;
        parent::tearDown();
    }

    public function testConstructor()
    {
        $this->assertFalse($this->adwords->hasActiveConversion());
        $this->assertNull($this->adwords->getActiveConversion());
    }

    public function testActivateGetConversion()
    {
        $this->adwords->activateConversionByKey('incorrect_conversion');
        $this->assertFalse($this->adwords->hasActiveConversion());

        $this->adwords->activateConversionByKey('account_create');
        $this->assertTrue($this->adwords->hasActiveConversion());

        $this->assertNotNull($this->adwords->getActiveConversion());

        // Object will remain in service for duration of execution
        $this->assertNotNull($this->adwords->getActiveConversion());
    }
}
