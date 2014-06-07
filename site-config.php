<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 2/21/14
 * Time: 9:38 PM
 */

define('DEV_MODE', true);

$mail_config = array(
    'host' => "mail server",
    'port' => "587",#mail port
    'auth' => true,
    'username' => 'user@mail',
    'password' => 'passowrd',
    'from' => 'user@mail',
    'from_name' => 'from name',
);

$conn = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'initiator',
    'user' => 'database user',
    'password' => 'user password',
);