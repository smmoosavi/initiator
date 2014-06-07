<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 3/7/14
 * Time: 1:21 PM
 */

$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/doctrine/config/yaml"), DEV_MODE);
$config->setProxyDir(__DIR__ . '/doctrine/proxies');



$entityManager = EntityManager::create($conn, $config);
$em = $entityManager;