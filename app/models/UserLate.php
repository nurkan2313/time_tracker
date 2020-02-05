<?php

namespace Timetracker\Models;

class UserLate extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $day;

    /**
     *
     * @var integer
     */
    protected $month;

    /**
     *
     * @var string
     */
    protected $month_name;

    /**
     *
     * @var integer
     */
    protected $year;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field day
     *
     * @param integer $day
     * @return $this
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Method to set the value of field month
     *
     * @param integer $month
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Method to set the value of field month_name
     *
     * @param string $month_name
     * @return $this
     */
    public function setMonthName($month_name)
    {
        $this->month_name = $month_name;

        return $this;
    }

    /**
     * Method to set the value of field year
     *
     * @param integer $year
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field day
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Returns the value of field month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Returns the value of field month_name
     *
     * @return string
     */
    public function getMonthName()
    {
        return $this->month_name;
    }

    /**
     * Returns the value of field year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("time_tracker");
        $this->setSource("user_late");
        $this->belongsTo('user_id', 'Timetracker\Models\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserLate[]|UserLate|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserLate|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
