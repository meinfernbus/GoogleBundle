<?php

namespace AntiMattr\GoogleBundle\Helper;

use AntiMattr\GoogleBundle\Adwords;
use Symfony\Component\Templating\Helper\Helper;

class AdwordsHelper extends Helper
{
    private $adwords;

    public function __construct(Adwords $adwords)
    {
        $this->adwords = $adwords;
    }

    /**
     * @param string $key
     */
    public function activateConversionByKey($key)
    {
        return $this->adwords->activateConversionByKey($key);
    }

    public function getActiveConversion()
    {
        return $this->adwords->getActiveConversion();
    }

    public function hasActiveConversion()
    {
        return $this->adwords->hasActiveConversion();
    }

    public function getName()
    {
        return 'google_adwords';
    }
}
