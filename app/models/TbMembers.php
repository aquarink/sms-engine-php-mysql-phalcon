<?php

class TbMembers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_member;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $telco;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
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
     * @Column(type="string", length=100, nullable=true)
     */
    public $app;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $join_date;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $reg_types;

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
        return 'tb_members';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbMembers[]|TbMembers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbMembers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
