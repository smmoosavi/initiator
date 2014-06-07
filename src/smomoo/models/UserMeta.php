<?php

namespace smomoo\models;

use Doctrine\ORM\Mapping as ORM;
use smomoo\models\Meta;
use smomoo\models\Model;

/**
 * UserMeta
 */
class UserMeta extends Meta
{
    static function getEntityName()
    {
        return 'smomoo\models\UserMeta';
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \smomoo\models\User
     */
    private $obj;


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
     * Set obj
     *
     * @param \smomoo\models\Metable $obj
     * @return UserMeta
     */
    public function setObj(\smomoo\models\Metable $obj = null)
    {
        $this->obj = $obj;

        return $this;
    }

    /**
     * Get obj
     *
     * @return \smomoo\models\User
     */
    public function getObj()
    {
        return $this->obj;
    }
}
