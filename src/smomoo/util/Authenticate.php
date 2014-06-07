<?php
/**
 * Created by JetBrains PhpStorm.
 * User: musavi.m
 * Date: 4/29/13
 * Time: 5:06 PM
 * To change this template use File | Settings | File Templates.
 */

namespace smomoo\util;


class Authenticate
{

    public static function hashPassword($password)
    {
        $hash = crypt($password, '$2a$10$' . substr(sha1(mt_rand()), 0, 22));
        return $hash;
    }

    public static function checkPassword($hash, $password)
    {
        $full_salt = substr($hash, 0, 29);
        $new_hash = crypt($password, $full_salt);
        return ($hash == $new_hash);
    }

    public static function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
}