<?php

class TbAppConfig extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_app;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $app_desc;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $cost_pull;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $cost_push;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $push_time;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $app_create;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $partner;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $contact;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $marketing;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $pic;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $now_date;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $config_status;

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
        return 'tb_app_config';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbAppConfig[]|TbAppConfig|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbAppConfig|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
