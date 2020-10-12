<?php

namespace AntiMattr\GoogleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GoogleExtension extends Extension
{
    /**
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $modules = [
            'adwords' => [],
            'analytics' => [],
            'maps' => [],
            'tagManager' => [],
        ];

        foreach ($configs as $config) {
            foreach (array_keys($modules) as $module) {
                if (array_key_exists($module, $config)) {
                    $modules[$module][] = isset($config[$module]) ? $config[$module] : [];
                }
            }
        }

        foreach (array_keys($modules) as $module) {
            if (!empty($modules[$module])) {
                call_user_func([$this, $module . 'Load'], $modules[$module], $container);
            }
        }
    }

    private function adwordsLoad(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('adwords.xml');

        foreach ($configs as $config) {
            if (isset($config['conversions'])) {
                $container->setParameter('google.adwords.conversions', $config['conversions']);
            }
        }
    }

    private function analyticsLoad(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('analytics.xml');

        foreach ($configs as $config) {
            if (isset($config['trackers'])) {
                $container->setParameter('google.analytics.trackers', $config['trackers']);
            }
            if (isset($config['dashboard'])) {
                $container->setParameter('google.analytics.dashboard', $config['dashboard']);
            }
            if (isset($config['whitelist'])) {
                $container->setParameter('google.analytics.whitelist', $config['whitelist']);
            }
            if (isset($config['js_source_https'])) {
                $container->setParameter('google.analytics.js_source_https', $config['js_source_https']);
            }
            if (isset($config['js_source_http'])) {
                $container->setParameter('google.analytics.js_source_http', $config['js_source_http']);
            }
            if (isset($config['js_source_endpoint'])) {
                $container->setParameter('google.analytics.js_source_endpoint', $config['js_source_endpoint']);
            }
        }
    }

    private function mapsLoad(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('maps.xml');

        foreach ($configs as $config) {
            if (isset($config['config'])) {
                $container->setParameter('google.maps.config', $config['config']);
            }
        }
    }

    private function tagManagerLoad(array $configs, ContainerBuilder $container)
    {
        $containers = [];
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('tag-manager.xml');

        foreach ($configs as $config) {
            if (isset($config['container'])) {
                $containers[] = $config['container'];
            }
            if (isset($config['containers'])) {
                $containers = array_merge($containers, array_values($config['containers']));
            }
        }

        $container->setParameter('google.tag_manager.containers', $containers);
    }

    /**
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::getAlias()
     * @codeCoverageIgnore
     */
    public function getAlias()
    {
        return 'google';
    }
}
