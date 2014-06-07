<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 2/21/14
 * Time: 9:51 PM
 */

namespace smomoo\views;

use smomoo\models\User;
use smomoo\util\App;

class Panel
{
    public static function init()
    {
        if (!is_null(User::getUser('admin'))) {
            App::pass();
        }
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword('123123');
        $user->setMail('');
        $user->setStatus(User::STATUS_ENABLE);
        $user->setType(User::TYPE_ADMIN);
        $user->persist($user);
    }

    public static function index()
    {
        if (User::authentication()) {
            App::render('panel.twig');
        } else {
            App::render('index.twig');
        }
    }


}