<?php
/**
 * Created by JetBrains PhpStorm.
 * User: musavi.m
 * Date: 4/29/13
 * Time: 5:46 PM
 * To change this template use File | Settings | File Templates.
 */

namespace smomoo\util;

use Twig_Environment;
use Twig_Loader_String;

class App
{

    public static function redirectAbsolute($url = null)
    {
        global $app;
        Message::keepMessages();
        $app->redirect($url);
    }

    public static function redirectRelative($url = null)
    {
        global $app;
        Message::keepMessages();
        $app->redirect(ROOT_URL . $url);
    }

    public static function redirect($urlName = null, $params = array())
    {
        global $app;
        Message::keepMessages();
        $app->redirect($app->urlFor($urlName, $params));
    }


    public static function render($template = null, $obj = array())
    {
        global $app;
        if (is_null($template)) {
            $app->response()->header('Content-type', 'application/json');
            $obj['msgs'] = Message::getMessages();
            echo json_encode($obj);
            $app->stop();
        } else {
            $app->view()->appendData(array(
                'msgs' => Message::getMessages()
            ));
            $app->render($template, $obj);
        }
    }

    public static function renderToStr($template = null, $obj = array())
    {
        global $app;
        $app->view()->appendData(array(
            'msgs' => Message::getMessages()
        ));
        return $app->view()->fetch($template, $obj);
    }

    public static function renderStr($template = '', $data = array())
    {
        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);
        return $twig->render($template, $data);
    }

    public static function pass()
    {
        global $app;
        $app->pass();
    }

    public static function isAjax()
    {
        global $app;
        return $app->request()->isAjax();
    }

    public static function passAjax()
    {
        global $app;
        if ($app->request()->isAjax()) {
            $app->pass();
        }
    }

    public static function confirmAjax()
    {
        global $app;
        if (!$app->request()->isAjax()) {
            $app->pass();
        }
    }

    public static function urlName()
    {
        global $app;
        return $app->router()->getCurrentRoute()->getName();
    }

}