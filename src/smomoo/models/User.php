<?php

namespace smomoo\models;

use Doctrine\ORM\Mapping as ORM;
use smomoo\models\Metable;
use smomoo\models\Model;
use smomoo\util\App;
use smomoo\util\Authenticate;

/**
 * User
 */
class User extends Metable
{
    static function getEntityName()
    {
        return 'smomoo\models\User';
    }

    const PATTERN_PASSWORD = '/^.{6,}$/';

    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';

    const STATUS_ENABLE = 'enable';
    const STATUS_DISABLE = 'disable';

    public static $login_url_name = 'login';
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */

    private $password;
    /**
     * @var string
     */

    private $mail;
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $metas;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = Authenticate::hashPassword($password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set mail
     *
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add metas
     *
     * @param \smomoo\models\UserMeta $metas
     * @return User
     */
    public function addMeta(\smomoo\models\UserMeta $metas)
    {
        $this->metas[] = $metas;

        return $this;
    }

    /**
     * Remove metas
     *
     * @param string $key
     * @return bool|void
     */
    public function removeMeta($key)
    {
        parent::removeMeta($key);
    }

    /**
     * Get metas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetas()
    {
        return $this->metas;
    }

    public function checkPassword($password)
    {
        if (!Authenticate::checkPassword($this->password, $password)) {
            return false;
        }
        return true;
    }

    public static function checkPasswordPattern($password)
    {
        if (preg_match(self::PATTERN_PASSWORD, $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $username
     * @return User
     */
    public static function getUser($username)
    {
        /* @var $user User */
        $user = self::getRepository()->findOneBy(array('username' => $username));
        return $user;
    }

    public static function findUser($username, $password)
    {
        /* @var $user User */
        $user = self::getUser($username);
        if (is_null($user)) {
            return null;
        }
        if (!$user->checkPassword($password)) {
            return null;
        }
        return $user;
    }

    private static $authenticated_user = null;

    public static function authentication()
    {
        if (is_null(self::$authenticated_user)) {
            if (isset($_SESSION['user.id'])) {
                self::$authenticated_user = self::getRepository()->find($_SESSION['user.id']);
            }
        }
        return self::$authenticated_user;
    }

    /**
     * @return User
     */
    public static function loginRequire()
    {
        if (isset($_SESSION['user.id'])) {
            return self::getRepository()->find($_SESSION['user.id']);
        }
        App::redirect(self::$login_url_name);
        return null;
    }

    public static function login($user)
    {
        session_destroy();
        session_start();
        $_SESSION['user.id'] = $user->id;
    }

    public static function logout()
    {
        session_destroy();
    }

    public function setRecoveryPasswordCode($c)
    {
        $this->setMeta('recovery-password-code', $c);
    }

    public function removeRecoveryPasswordCode()
    {
        $this->removeMeta('recovery-password-code');
    }

    /**
     * @param $c
     * @return User
     */
    public static function getByRecoveryPasswordCode($c)
    {
        /* @var $meta UserMeta */
        $meta = UserMeta::findOneBy(array('key' => 'recovery-password-code', 'value' => $c));
        if (is_null($meta)) {
            return null;
        }
        $contestant = $meta->getObj();
        return $contestant;
    }
}
