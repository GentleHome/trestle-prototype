<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once dirname(__FILE__) . "/vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// or if you prefer yaml or XML
// $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
// $config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'driver' => 'pdo_pgsql',
    'user' => 'lzccendxqkvvuk',
    'dbname' => 'datr0o2ti8vsvk',
    'password' => '4243b5ffcee2ddab9427a80d054134b94f03c8039f9ab66d64a07e578916deb1',
    'host' => 'ec2-54-89-105-122.compute-1.amazonaws.com',
    'port' => '5432',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
