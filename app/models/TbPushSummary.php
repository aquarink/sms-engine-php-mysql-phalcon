<?php

class TbPushSummary extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=250, nullable=true)
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
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $response_code;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $subject;

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
        return 'tb_push_summary';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbPushSummary[]|TbPushSummary|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbPushSummary|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
