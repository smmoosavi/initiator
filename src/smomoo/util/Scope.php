<?php
/**
 * Created by JetBrains PhpStorm.
 * User: musavi.m
 * Date: 4/29/13
 * Time: 5:46 PM
 * To change this template use File | Settings | File Templates.
 */

namespace smomoo\util;

class Scope
{
   private static $values = array();

    public static function set($key, $value)
    {
        self::$values[$key] = $value;
    }

    public static function get($key)
    {
        if (isset(self::$values[$key])) {
            return self::$values[$key];
        }
        return null;
    }
}