<?php

class TbTelcoConfig extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_telco;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $telco_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $push_limit;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=true)
     */
    public $address;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $shortname;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smsgw_engine_db");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tb_telco_config';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbTelcoConfig[]|TbTelcoConfig|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbTelcoConfig|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
