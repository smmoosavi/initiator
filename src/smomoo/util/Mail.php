<?php
/**
 * Created by JetBrains PhpStorm.
 * User: musavi.m
 * Date: 5/21/13
 * Time: 4:14 PM
 * To change this template use File | Settings | File Templates.
 */

namespace smomoo\util;


use PHPMailer;

class Mail
{

    static $config = null;

    public static function setConfig($config)
    {
        Mail::$config = $config;
    }

    /**
     * @return PHPMailer
     */
    public static function getMailer()
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = self::$config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = self::$config['username'];
        $mail->Password = self::$config['password'];
        $mail->SMTPSecure = 'tls';

        $mail->From = self::$config['from'];
        $mail->FromName = self::$config['from_name'];

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        return $mail;
    }

    public static function validateMail($mail)
    {
        preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $mail, $match);
        return !empty($match);
    }
}