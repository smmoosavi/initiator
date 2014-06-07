<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 2/21/14
 * Time: 9:51 PM
 */

namespace smomoo\views;


use smomoo\util\App;
use smomoo\util\Captcha;

class Common
{


    public static function index()
    {
        App::render('index.twig');
    }

    public static function captcha()
    {
        global $app;
        $app->contentType('image/jpeg');
        Captcha::generate();
    }

    public static function test()
    {
        echo '<pre>';

    }

    public static function dashboard()
    {
        App::render('dashboard.twig');
    }


} 