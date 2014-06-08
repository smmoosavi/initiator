<?php
use smmoosavi\util\gettext\L10n;
use smomoo\models\User;

/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 2/21/14
 * Time: 9:30 PM
 */

session_start();
require_once 'site-config.php';
require_once 'db-config.php';
if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/********************************************/
/*              slim configs                */
/********************************************/


$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));
$app->config('debug', DEV_MODE);
$app->config('cookies.httponly', true);

$app->add(new \smomoo\util\Csrf());
$app->hook('slim.after', 'smomoo\util\Message::clearMessage');

/********************************************/
/*                   urls                   */
/********************************************/

$app->hook('slim.before.router', function () use ($app) {
    $path = $app->request()->getPath();
    if (!preg_match("|/$|", $path)) {
        $app->redirect($path . '/');
    }
    $uri = $app->request()->getRootUri();
    if (preg_match("|index.php$|", $uri)) {
        $app->notFound();
    }
});

$ROOT_URL = $app->request()->getRootUri();
$ROOT_URL = preg_replace("|/index.php$|", "", $ROOT_URL);
$REQUEST_URL = $app->request()->getResourceUri();
define('ABS_PATH', dirname(__FILE__) . '/');
define('ROOT_URL', $ROOT_URL);

define('REQUEST_URL', $REQUEST_URL);

/********************************************/
/*              twig configs                */
/********************************************/

/* @var $view \Slim\Views\Twig */
$view = $app->view();
$env = $view->getEnvironment();

$env->addExtension(new \Slim\Views\TwigExtension());
$env->addExtension(new \smomoo\util\TwigExtension());
$view->twigTemplateDirs = array('templates');


if (DEV_MODE) {
    $env->enableDebug();
} else {
    $env->disableDebug();
}

$env->addGlobal('_user', User::authentication());

$env->addGlobal('ROOT_URL', ROOT_URL);
$env->addGlobal('_url', REQUEST_URL);

$locale = 'fa_IR';
$lang = 'fa';
L10n::init($lang, __DIR__ . "/locale/$locale/LC_MESSAGES/messages.mo");

// initializing twig-php-gettext
$env->addExtension(new smmoosavi\util\twiggettext\Extension_L10n());