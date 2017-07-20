<?php

class TbAppsContent extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_content;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $id_app;

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
     * @Column(type="string", length=20, nullable=true)
     */
    public $content_create;

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
        return 'tb_apps_content';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbAppsContent[]|TbAppsContent|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TbAppsContent|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
