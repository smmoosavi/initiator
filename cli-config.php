<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 3/7/14
 * Time: 1:25 PM
 */

require_once "packages/autoload.php";
require_once 'site-config.php';
require_once "db-config.php";

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);