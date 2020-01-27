<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.01.2020
 * Time: 15:18
 */

namespace Dates\DTO;


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

}