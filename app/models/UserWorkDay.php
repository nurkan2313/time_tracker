<?php

namespace Timetracker\Models;

class UserWorkDay extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var double
     */
    public $total_work_hour;

    /**
     *
     * @var double
     */
    public $remain;

    /**
     *
     * @var string
     */
    public $day;

    /**
     *
     * @var double
     */
    public $start_time;

    /**
     *
     * @var double
     */
    public $end_time;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $time_dimension_id;

    public $month;

    public $year;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("time_tracker");
        $this->setSource("user_work_day");
        $this->belongsTo('user_id', 'Timetracker\Models\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('time_dimension_id', 'Timetracker\Models\TimeDimension', 'id', ['alias' => 'TimeDimension']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserWorkDay[]|UserWorkDay|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserWorkDay|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
