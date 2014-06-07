<?php
/**
 * Created by PhpStorm.
 * User: smomoo
 * Date: 3/13/14
 * Time: 9:07 AM
 */

namespace smomoo\models;



abstract class Meta extends Model
{
    /**
     * @var string
     *
     */
    protected $key;

    /**
     * @var string
     *
     */
    protected $type;

    /**
     * @var string
     *
     */
    protected $value;


    /**
     * Set key
     *
     * @param string $key
     * @return Meta
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Meta
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
     * Set value
     *
     * @param string $value
     * @param null $type
     * @param bool $raw
     * @return Meta
     */
    public function setValue($value,$type=null,$raw=false)
    {
        if ($raw) {
            $this->value = $value;
            return $this;
        }
        $tv = Model::o2tv($value,$type);// TODO
        $this->value = $tv['value'];
        $this->type = $tv['type'];

        return $this;
    }

    /**
     * Get value
     *
     * @param bool $raw
     * @return string
     */
    public function getValue($raw=false)
    {
        if ($raw) {
            return $this->value;
        }
        return Model::tv2o($this->type, $this->value); // TODO
    }

    /**
     * Set obj
     *
     * @param \smomoo\models\Metable $obj
     * @return Meta
     */
    public abstract function setObj(\smomoo\models\Metable $obj = null);

    /**
     * Get obj
     *
     * @return \smomoo\models\Metable
     */
    public abstract function getObj();
}
