<?php

require_once $_SERVER['VENDOR_LIB'].'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                                           => $_SERVER['VENDOR_LIB'].'/symfony/src',
    'AntiMattr'                                         => __DIR__.'/../../../../',
    'Doctrine\\Common\\DataFixtures'                    => $_SERVER['VENDOR_LIB'].'/doctrine-data-fixtures/lib',
    'Doctrine\\Common'                                  => $_SERVER['VENDOR_LIB'].'/doctrine-common/lib',
    'Doctrine\\DBAL\\Migrations'                        => $_SERVER['VENDOR_LIB'].'/doctrine-migrations/lib',
    'Doctrine\\DBAL'                                    => $_SERVER['VENDOR_LIB'].'/doctrine-dbal/lib',
    'Doctrine\\ORM'                                     => $_SERVER['VENDOR_LIB'].'/doctrine/lib',
    'Zend'                                              => $_SERVER['VENDOR_LIB'].'/zend/library'
));
$loader->registerPrefixes(array(
    'Swift_'     => $_SERVER['VENDOR_LIB'].'/swiftmailer/lib/classes',
    'Twig_'      => $_SERVER['VENDOR_LIB'].'/twig/lib'
));
$loader->registerNamespaceFallbacks(array($_SERVER['VENDOR_LIB'].'/bundles'));
$loader->register();
