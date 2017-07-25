<?php

class TbReportPush extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_report;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $id_app;

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
    public $type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $cost;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $subject;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $dr_status;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $hit_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $total;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=true)
     */
    public $report_date;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $report_create;

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
        return 'tb_report_push';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbReportPush[]|TbReportPush|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbReportPush|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
