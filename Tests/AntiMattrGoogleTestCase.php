<?php

namespace AntiMattr\GoogleBundle\Tests;

use AntiMattr\TestCase\AntiMattrTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @codeCoverageIgnore
 */
abstract class AntiMattrGoogleTestCase extends AntiMattrTestCase
{
    protected $container;
    protected $session;
    protected $storage;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->storage = new MockArraySessionStorage();
        $this->session = new Session($this->storage);

        $this->container->set('session', $this->session);
    }
}
