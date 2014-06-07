<?php
/**
 * Created by JetBrains PhpStorm.
 * User: musavi.m
 * Date: 5/8/13
 * Time: 11:21 AM
 * To change this template use File | Settings | File Templates.
 */

namespace smomoo\util;



use smomoo\util\lib\PhpCaptcha;

class Captcha
{
    /**
     * need to set content to
     */
    public static function generate($w = 182, $h = 30, $n = 6)
    {
// define fonts
        $aFonts = array(ABS_PATH . 'private/captcha/fonts/PanicStricken.ttf');
// create new image
        $oPhpCaptcha = new PhpCaptcha($aFonts, $w, $h);
        $oPhpCaptcha->SetBackgroundImages(ABS_PATH . 'private/captcha/images/gray.jpg');
        $oPhpCaptcha->SetNumChars($n);
        $oPhpCaptcha->Create();
    }

    public static function validate($input_name = 'captcha')
    {
        global $app;
        $captcha = $app->request()->post($input_name);
        return PhpCaptcha::Validate($captcha) === true;
    }
}