<?php

class TbSmsPush extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_push;

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
     * @Column(type="string", length=200, nullable=true)
     */
    public $sms_field;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $keyword;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $content_number;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $content_field;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $trx_id;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $trx_date;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $session_id;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $session_date;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $reg_type;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $type;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $cost;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $send_status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("new_sms_2");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tb_sms_push';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbSmsPush[]|TbSmsPush|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbSmsPush|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
