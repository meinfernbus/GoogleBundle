<?php

declare(strict_types=1);

namespace AntiMattr\GoogleBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IntegrationTestCase extends WebTestCase
{
    /**
     * Provides forward compatibility with symfony 4.x and 5.x
     */
    protected function getContainer(): ContainerInterface
    {
        if (isset(static::$container)) {
            return static::$container;
        } elseif (isset(static::$kernel)) {
            return self::$kernel->getContainer();
        }

        throw new \RuntimeException('Unable to determine container. Did you boot kernel?');
    }
}
