<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Adwords\Conversion;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Adwords
{
    const CONVERSION_KEY   = 'google_adwords/conversion';
    const CONVERSION_VALUE = 'google_adwords/conversion_value';

    private $activeConversion;
    private $container;
    private $conversions;

    public function __construct(ContainerInterface $container, array $conversions = array())
    {
        $this->container = $container;
        $this->conversions = $conversions;
    }

    /**
     * @param string $key
     * @param float  $value
     */
    public function activateConversionByKey($key, $value = null)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session */
        $session = $this->container->get('session');
        if (array_key_exists($key, $this->conversions)) {
            $session->set(self::CONVERSION_KEY, $key);
            if (!is_null($value)) {
                $session->set(self::CONVERSION_VALUE, $value);
            } else {
                $session->remove(self::CONVERSION_VALUE);
            }
        }
    }	

    /**
     * @return Conversion $conversion
     */
    public function getActiveConversion()
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session */
        $session = $this->container->get('session');
        if ($this->hasActiveConversion()) {
            $key = $session->get(self::CONVERSION_KEY);
            $session->remove(self::CONVERSION_KEY);
            $config = $this->conversions[$key];
            if ($session->has(self::CONVERSION_VALUE)) {
                $config['value'] = $session->get(self::CONVERSION_VALUE);
            }
            $this->activeConversion = new Conversion($config['id'], $config['label'], $config['value'], $config['remarketing']);
        }
        return $this->activeConversion;
    }

    /**
     * @param boolean $hasActiveConversion
     */
    public function hasActiveConversion()
    {
        return $this->container->get('session')->has(self::CONVERSION_KEY);
    }
}
