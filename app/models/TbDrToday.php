<?php

class TbDrToday extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_dr;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $telco;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $shortcode;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $msisdn;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $trx_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $trx_date;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $session_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $session_date;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $stat;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("smsgw_engine_log");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tb_dr_today';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbDrToday[]|TbDrToday|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbDrToday|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
