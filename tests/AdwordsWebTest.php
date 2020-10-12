<?php

namespace AntiMattr\GoogleBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdwordsWebTest extends WebTestCase
{
    private $adwords;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->adwords = static::$container->get('google.adwords');
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
