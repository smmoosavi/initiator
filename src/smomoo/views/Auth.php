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
use smomoo\util\Captcha;
use smomoo\util\Mail;
use smomoo\util\Authenticate;
use smomoo\util\Message;

class Auth
{

    public static function login()
    {
        $user = User::authentication();
        if (!is_null($user)) {
            App::redirect('index');
        }
        App::render('auth/login.twig');
    }

    public static function login_post()
    {
        global $app;
        $user = User::authentication();
        if (!is_null($user)) {
            App::redirect('index');
        }
        $captcha = Captcha::validate();
        if (!$captcha) {
            Message::addMessage('Invalid captcha', Message::WARNING);
            App::redirect('login');
        }
        $username = $app->request()->post('username');
        $password = $app->request()->post('password');
        $user = User::findUser($username, $password);
        if (is_null($user)) {
            Message::addMessage('Invalid username or password', Message::ERROR);
            App::redirect('login');
        }
        User::login($user);
        App::redirect('index');
    }

    public static function logout_post()
    {
        User::logout();
        App::redirect('index');
    }
    public static function change_password()
    {
        User::loginRequire();
        App::render('auth/change-password.twig');
    }

    public static function change_password_post()
    {
        global $app;
        $user = User::loginRequire();

        $old_password = $app->request()->post('old-password');
        $new_password = $app->request()->post('new-password');
        $repeat_password = $app->request()->post('repeat-password');

        $check = $user->checkPassword($old_password);

        if (!$check) {
            Message::clearMessage();
            Message::addMessage(__('Wrong old password'), Message::WARNING);
            App::redirect('change-password');
        }
        if ($new_password != $repeat_password) {
            Message::clearMessage();
            Message::addMessage(__('Different new password and repeat'), Message::WARNING);

            App::redirect('change-password');
        }
        if (!User::checkPasswordPattern($new_password)) {
            Message::clearMessage();
            Message::addMessage(__('Password must be at least 6 characters long'), Message::WARNING);

            App::redirect('change-password');
        }
        $user->setPassword($new_password);
        $user->persist();
        Message::clearMessage();
        Message::addMessage(__('Password changed'), Message::SUCCESS);
        App::redirect('change-password');
    }

    public static function recovery_password()
    {
        global $app;
        $user = User::authentication();
        if (!is_null($user)) {
            App::redirect('index');
        }
        $c = $app->request()->get('c');
        if (is_null($c)) {
            App::render('auth/recovery-password.twig');
        } else {
            $user = User::getByRecoveryPasswordCode($c);
            App::render('auth/recovery-confirm.twig', compact('c', 'user'));
        }

    }

    public static function recovery_password_post()
    {
        global $app;
        $user = User::authentication();
        if (!is_null($user)) {
            App::redirect('index');
        }

        $c = $app->request()->post('c');
        if (is_null($c)) {
            $captcha = Captcha::validate();
            if (!$captcha) {
                Message::addMessage('Invalid captcha', Message::WARNING);
                App::redirect('recovery-password');
            }

            $username = $app->request()->post('username');
            $user = User::getUser($username);
            if (is_null($user)) {
                Message::addMessage(__('username dose not exist'), Message::INFO);
                App::redirect('recovery-password');
            }
            $mail = $user->getMail();

            $c = Authenticate::generateRandomString(30);
            $user->setRecoveryPasswordCode($c);


            global $mail_config;
            Mail::setConfig($mail_config);
            $mailer = Mail::getMailer();
            $mailer->addAddress($mail);
            $mailer->isHTML(true);
            $mailer->Subject = __('recover password');
            $link = $app->request()->getUrl() . $app->urlFor('recovery-password') . "?c=$c";
            $mailer->Body = "username: <b>$username</b>\n<br>recovery link: <a href='$link'>$link</a>";
            $mailer->AltBody = "username: $username\n recovery link: $link";
            if (!$mailer->send()) {
                Message::addMessage(__('failed to send mail'), Message::ERROR);
                exit;
            } else {
                Message::addMessage(__('recovery link sent to your email'), Message::SUCCESS);
            }
            App::redirect('recovery-password');
        } else {
            $user = User::getByRecoveryPasswordCode($c);
            if (is_null($user)) {
                Message::addMessage(__('invalid recovery code'), Message::ERROR);
                App::redirect('recovery-password');
            }
            $password = Authenticate::generateRandomString(10);
            $mail = $user->getMail();
            $username = $user->getUsername();
            global $mail_config;
            Mail::setConfig($mail_config);
            $mailer = Mail::getMailer();
            $mailer->addAddress($mail);
            $mailer->isHTML(true);
            $mailer->Subject = __('recover password');
            $mailer->Body = "username: <b>$username</b>\n<br>recovery link: <b>$password</b>";
            $mailer->AltBody = "username: $username\n recovery link: $password";
            if (!$mailer->send()) {
                Message::addMessage(__('failed to send mail'), Message::ERROR);
                exit;
            } else {
                $user->setPassword($password);
                $user->persist();
                $user->removeRecoveryPasswordCode();
                Message::addMessage(__('new password sent to your email'), Message::SUCCESS);
            }
            App::redirect('recovery-password');
        }
    }
}