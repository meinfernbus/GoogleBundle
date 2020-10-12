<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Adwords\Conversion;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Adwords
{
    const CONVERSION_KEY = 'google_adwords/conversion';
    const CONVERSION_VALUE = 'google_adwords/conversion_value';

    /**
     * @var Conversion|null
     */
    private $activeConversion;
    private $conversions;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session, array $conversions = [])
    {
        $this->conversions = $conversions;
        $this->session = $session;
    }

    /**
     * @param string $key
     * @param float  $value
     */
    public function activateConversionByKey($key, $value = null)
    {
        if (array_key_exists($key, $this->conversions)) {
            $this->session->set(self::CONVERSION_KEY, $key);
            if (!is_null($value)) {
                $this->session->set(self::CONVERSION_VALUE, $value);
            } else {
                $this->session->remove(self::CONVERSION_VALUE);
            }
        }
    }

    public function getActiveConversion(): ?Conversion
    {
        if ($this->hasActiveConversion()) {
            $key = $this->session->get(self::CONVERSION_KEY);
            $this->session->remove(self::CONVERSION_KEY);
            $config = $this->conversions[$key];
            if ($this->session->has(self::CONVERSION_VALUE)) {
                $config['value'] = $this->session->get(self::CONVERSION_VALUE);
            }
            $this->activeConversion = new Conversion(
                $config['id'],
                $config['label'],
                $config['value'],
                isset($config['remarketing']) ? $config : false
            );
        }

        return $this->activeConversion;
    }

    public function hasActiveConversion(): bool
    {
        return $this->session->has(self::CONVERSION_KEY);
    }
}
