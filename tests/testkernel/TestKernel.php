<?php

namespace AntiMattr\GoogleBundle\Tests\testkernel;

use AntiMattr\GoogleBundle\GoogleBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerRootDir()
    {
        return __DIR__;
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TemplatingBundle(),

            new GoogleBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');

        if (file_exists(__DIR__ . '/config/parameters.yml')) {
            $loader->load(__DIR__ . '/config/parameters.yml');
        }
    }
}
