<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 3/13/14
 * Time: 9:07 AM
 */

namespace smomoo\models;


abstract class Metable extends Model
{


    /**
     * Remove metas
     *
     * @param string $key
     * @return bool
     */
    public function removeMeta($key)
    {
        $meta = $this->getMetas()->get($key);
        $this->getMetas()->removeElement($meta);
        if (isset($meta)) {
            return Meta::remove($meta);
        }
        return false;
    }

    /**
     * Get metas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public abstract function getMetas();

    public function getMeta($key)
    {
        $meta = $this->getMetas()->get($key);
        if (isset($meta)) {
            return $meta->getValue();
        } else {
            return null;
        }
    }

    public function setMeta($key, $value)
    {
        global $em;
        /* @var $meta Meta */
        $meta = $this->getMetas()->get($key);
        if (is_null($meta)) {
            $metaClass = static::getEntityName() . 'Meta';
            $meta = new $metaClass();
            $meta->setObj($this);
            $meta->setKey($key);
            $meta->setValue($value);
            $meta->persist();
        } else {
            $meta->setValue($value);
            $meta->persist();
        }
    }

    public function findMeta()
    {
        //TODO
    }

}
