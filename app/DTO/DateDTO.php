<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.01.2020
 * Time: 15:18
 */

namespace Dates\DTO;


use Timetracker\Models\TimeDimension;

class DateDTO
{
    private $year;
    private $month;
    private $day;
    private $timezone;

    public function __construct()
    {
        $this->timezone = date_default_timezone_set('Asia/Bishkek');
    }

    public function getYear() {
        $this->year = date('Y');
        return $this->year;
    }

    public function getMonth() {
        $this->month = date('n');
        return $this->month;
    }

    public function getDay() {
        $this->day = date('j');
        return $this->day;
    }

    public static function getYears () {
        $yearArray = array();

        $years = TimeDimension::find([
            'conditions' => 'year between :from: and :to:',
            'columns'    => 'distinct year',
            'bind'       => [
                'from' => 2020,
                'to'   => 2029
            ],
            'order' => 'year ASC'
        ]);

        foreach ($years as $year) {
            $yearArray[] = $year->year;
        }

        return $yearArray;
    }

}