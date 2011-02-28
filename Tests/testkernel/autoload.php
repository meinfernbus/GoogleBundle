<?php

require_once $_SERVER['VENDOR_LIB'].'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                                           => $_SERVER['VENDOR_LIB'].'/symfony/src',
    'AntiMattr'                                         => __DIR__.'/../../../../',
    'Doctrine\\ODM\\MongoDB\\Symfony\\SoftDeleteBundle' => $_SERVER['VENDOR_LIB'].'/doctrine-mongodb-odm-softdelete-bundle',
    'Doctrine\\ODM\\MongoDB\\SoftDelete'                => $_SERVER['VENDOR_LIB'].'/doctrine-mongodb-odm-softdelete/lib',
    'Doctrine\\Common\\DataFixtures'                    => $_SERVER['VENDOR_LIB'].'/doctrine-data-fixtures/lib',
    'Doctrine\\Common'                                  => $_SERVER['VENDOR_LIB'].'/doctrine-common/lib',
    'Doctrine\\DBAL\\Migrations'                        => $_SERVER['VENDOR_LIB'].'/doctrine-migrations/lib',
    'Doctrine\\DBAL'                                    => $_SERVER['VENDOR_LIB'].'/doctrine-dbal/lib',
    'Doctrine\\MongoDB'                                 => $_SERVER['VENDOR_LIB'].'/doctrine-mongodb/lib',
    'Doctrine\\ODM\\MongoDB'                            => $_SERVER['VENDOR_LIB'].'/doctrine-mongodb-odm/lib',
    'Zend'                                              => $_SERVER['VENDOR_LIB'].'/zend/library'
));
$loader->registerPrefixes(array(
    'Swift_'     => $_SERVER['VENDOR_LIB'].'/swiftmailer/lib/classes',
    'Twig_'      => $_SERVER['VENDOR_LIB'].'/twig/lib'
));
$loader->registerNamespaceFallback(__DIR__);
$loader->register();
