<?php

namespace Timetracker\Models;

class TimeDimension extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $db_date;

    /**
     *
     * @var integer
     */
    public $year;

    /**
     *
     * @var integer
     */
    public $month;

    /**
     *
     * @var integer
     */
    public $day;

    /**
     *
     * @var integer
     */
    public $quarter;

    /**
     *
     * @var integer
     */
    public $week;

    /**
     *
     * @var string
     */
    public $day_name;

    /**
     *
     * @var string
     */
    public $month_name;

    /**
     *
     * @var string
     */
    public $holiday_flag;

    /**
     *
     * @var string
     */
    public $weekend_flag;

    /**
     *
     * @var string
     */
    public $event;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("time_tracker");
        $this->setSource("time_dimension");
        $this->hasMany('id', 'Timetracker\Models\UserWorkDay', 'time_dimension_id', ['alias' => 'UserWorkDay']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TimeDimension[]|TimeDimension|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TimeDimension|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
