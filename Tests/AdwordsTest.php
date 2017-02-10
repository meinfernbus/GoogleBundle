<?php

namespace AntiMattr\GoogleBundle\Tests;

use AntiMattr\GoogleBundle\Adwords;
use AntiMattr\TestCase\AntiMattrTestCase;

class AdwordsWebTest extends AntiMattrGoogleTestCase
{
    private $adwords;
    private $configuration;

    protected function setUp()
    {
        parent::setUp();

        $this->configuration = array(
            'account_create' => array(
                'id' => '111111',
                'label' => 'accountCreateLabel',
                'value' => 0
            )
        );
        $this->adwords = new Adwords($this->container, $this->configuration);
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
