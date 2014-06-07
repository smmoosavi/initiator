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

class Message
{
    const ERROR = 'error';
    const  WARNING = 'warning';
    const INFO = 'info';
    const SUCCESS = 'success';

    private static $rtl = false;
    private static $keep = false;

    public static function flash($key, $value)
    {
        global $app;
        $app->flash($key, $value);
    }

    public static function addMessage($text, $type = 'info', $rtl = null, $link = null)
    {
        if (!isset($_SESSION['smomoo.msg'])) {
            Message::clearMessage();
        }
        if (is_null($rtl)) {
            if (self::$rtl) {
                $rtl = false;
            } else {
                $rtl = true;
            }
        }
        $_SESSION['smomoo.msg'][] = array(
            'text' => $text,
            'type' => $type,
            'link' => $link,
            'direction' => $rtl ? 'rtl' : 'ltr',
        );
    }

    public static function clearMessage()
    {
        if (self::$keep) {
            return;
        }
        $_SESSION['smomoo.msg'] = array();
    }

    public static function getMessages()
    {
        if (isset($_SESSION['smomoo.msg'])) {
            return $_SESSION['smomoo.msg'];
        } else {
            return array();
        }
    }

    public static function hasMessage()
    {
        if (!empty($_SESSION['smomoo.msg'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function keepMessages()
    {
        global $app;
        $app->flashKeep();
        self::$keep = true;
    }

}