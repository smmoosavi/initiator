<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 3/13/14
 * Time: 9:07 AM
 */

namespace smomoo\models;


use Doctrine\DBAL\LockMode;
use Symfony\Component\Yaml\Yaml;

abstract class Model implements Entity
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public static function getRepository()
    {
        global $em;
        return $em->getRepository(static::getEntityName());
    }

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     * @param int $lockMode The lock mode.
     * @param int|null $lockVersion The lock version.
     *
     * @return Model|null The entity instance or NULL if the entity can not be found.
     */
    public static function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return self::getRepository()->find($id, $lockMode, $lockVersion);
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public static function findAll()
    {
        return self::getRepository()->findAll();
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array The Models.
     */
    public static function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {


        return self::getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return Model|null The entity instance or NULL if the entity can not be found.
     */
    public static function findOneBy(array $criteria, array $orderBy = null)
    {

        return self::getRepository()->findOneBy($criteria, $orderBy);
    }

    /**
     *
     */
    public function persist()
    {
        global $em;
        $em->persist($this);
        $em->flush();
    }

    public function refresh()
    {
        global $em;
        $em->refresh($this);
    }

    /**
     * @param Model $obj
     * @return bool
     */
    public static function remove($obj)
    {
        global $em;
        try {
            $em->remove($obj);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public static function fields()
    {
        global $em;
        $class = $em->getClassMetadata(static::getEntityName());
        return $class->getFieldNames();
    }

    public static function associations()
    {
        global $em;
        $class = $em->getClassMetadata(static::getEntityName());
        return $class->getAssociationNames();
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function fieldValue($field)
    {
        $keys = explode('.', $field);
        $obj = $this;
        foreach ($keys as $key) {
            $getter_name = 'get' . ucfirst($key);

            if (isset($obj->$key)) {
                $obj = $obj->$key;
            } elseif (is_callable(array($obj, $getter_name))) {
                $obj = $obj->$getter_name();
            }
        }
        if ($obj instanceof Model) {
            return $obj->toArray();
        }
        if ($obj instanceof \Doctrine\Common\Collections\Collection) {
            $objs = array();
            foreach ($obj as $o) {
                if ($o instanceof Model) {
                    $objs[] = $o->toArray();
                } else {
                    $objs[] = $o;
                }
            }
            return $objs;
        }
        return $obj;
    }

    /**
     * @param array $fields
     * @return array
     */
    public function toArray($fields = array())
    {
        if (empty($fields)) {
            $fields = self::fields();
        }
        $out = array();
        foreach ($fields as $field) {
            $out[$field] = $this->fieldValue($field);
        }
        return $out;
    }

    /**
     * @param mixed $obj
     * @param string $type
     * @return array
     */
    public static function o2tv($obj, $type = null)
    {
        if (is_null($type)) {
            $type = gettype($obj);
        }
        if ($obj instanceof \DateTime) {
            $type = 'DateTime';
            $value = $obj->format('Y-m-d H:i:s');
        } elseif ($type == 'json') {
            $value = json_encode($obj);
        } elseif (is_array($obj) or $type == 'yaml'or $type == 'array') {
            $type = 'yaml';
            $value = Yaml::dump($obj, 4, 2);
        } else {
            $value = (string)$obj;
        }
        return compact('type', 'value');
    }

    /**
     * @param $type
     * @param $value
     * @return array|bool|\DateTime|int|mixed|null
     */
    public static function tv2o($type, $value)
    {
        $obj = null;
        if ($type == 'DateTime') {
            $obj = new \DateTime($value);
        } elseif ($type == 'yaml') {
            $obj = Yaml::parse($value);
        } elseif ($type == 'json') {
            $obj = json_decode($value, true);
        } elseif ($type == 'NULL') {
            $obj = null;
        } elseif ($type == 'boolean') {
            $obj = (boolean)$value;
        } elseif ($type == 'integer') {
            $obj = intval($value);
        } elseif ($type == 'double') {
            $obj = doubleval($value);
        } else {
            return $value;
        }
        return $obj;
    }

    public static function rawSql($sql, $params=array())
    {
        global $em;
        $qb = $em->getConnection()->prepare(
            $sql
        );
        $qb->execute($params);
        $res = $qb->fetchAll();
        return $res;
    }
}
