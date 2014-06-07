<?php
use smomoo\util\App;

/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 2/21/14
 * Time: 9:30 PM
 */


$app->get('/init-admin/', 'smomoo\views\Panel::init')->name('init');

$app->get('/', 'smomoo\views\Common::index')->name('index');
$app->get('/dashboard/', 'smomoo\views\Common::dashboard')->name('dashboard');

$app->get('/captcha/', 'smomoo\views\Common::captcha')->name('captcha');


$app->get('/login/', 'smomoo\views\Auth::login')->name('login');
$app->post('/login/', 'smomoo\views\Auth::login_post')->name('login-post');

$app->post('/logout/', 'smomoo\views\Auth::logout_post')->name('logout-post');


$app->get('/change-password/', 'smomoo\views\Auth::change_password')->name('change-password');
$app->post('/change-password/', 'smomoo\views\Auth::change_password_post')->name('change-password-post');

$app->get('/recovery-password/', 'smomoo\views\Auth::recovery_password')->name('recovery-password');
$app->post('/recovery-password/', 'smomoo\views\Auth::recovery_password_post')->name('recovery-password-post');

$app->get('/test/', 'smomoo\views\Common::test')->name('test');