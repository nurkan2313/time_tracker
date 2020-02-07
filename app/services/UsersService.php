<?php

namespace Timetracker\Services;
use Dates\DTO\DateDTO;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use phpDocumentor\Reflection\Types\This;
use Timetracker\Helper\Helpers;
use Timetracker\Models\StartWorkHour;
use Timetracker\Models\TimeDimension;
use Timetracker\Models\UserLate;
use Timetracker\Models\Users;
use Timetracker\Models\UserWorkDay;

class UsersService extends Injectable
{
    public $timeDimension;
    public $percentResult;
    public $users;
    public $calendar;
    public $days;
    public $year;
    public $month;
    public $drawTable;

    public function initialize()
    {
        $this->timeDimension = new TimeDimension();
        $this->users = new Users();
        $this->calendar = new TimeDimension();
    }

    private static function checkIfUserComeOnTime($start_time) {

        $getTime = StartWorkHour::findFirst(1);
        if($start_time > $getTime->getTime()) {
            return false;
        }
        return true;
    }

    public function getUserWorkDay(Request $request): array {
        $this->drawTable = new DrawTableService();
        return $this->drawTable->draw($request);
    }

    public function allCurrentMonthDaysArray() {
        return $this->days;
    }

    public function getUsers() {
        $userBuilder = $this->modelsManager->createBuilder();
        $userBuilder->columns(['Timetracker\Models\Users.name as name, Timetracker\Models\Users.id as user_id'])
            ->from('Timetracker\Models\Users')
            ->orderBy('Timetracker\Models\Users.id');
        $data = $userBuilder->getQuery()->execute();
        return $data;
    }

    public function calculateUserTotalHour() {
        $dates = new DateDTO();
        $total = 0;
        $result =  UserWorkDay::find( [
            'conditions' => 'user_id = :user_id: and year = :year: and month = :month:',
            'bind'       => [
                'user_id' => $this->session->get('AUTH_ID'),
                'year' => $dates->getYear(),
                'month' => $dates->getMonth(),
            ]
        ]);

        foreach ($result as $calculateUserStat) {
            $total += ($calculateUserStat->end_time - $calculateUserStat->start_time);
        }

        return $total;
    }

    public function totalHourPerMonth() {
        $dates   = new DateDTO();
        $totalDays = array();

        $calendar = TimeDimension::find( [
            'conditions' => 'year = :year: and month = :month: and holiday_flag = :offday: and weekend_flag = :offday:',
            'bind'       => [
                'year' => $dates->getYear(),
                'month' => $dates->getMonth(),
                'offday' => 'f'
            ]
        ]);

        foreach ($calendar as $cal) {
            array_push($totalDays, $cal->day);
        }

        $result = 9 * count($totalDays);
        return $result;
    }

    public function calculateAssignedHour() {

        $hundredPercent = $this->totalHourPerMonth();
        $userHours = $this->calculateUserTotalHour();

        if($userHours > 0) {
            $this->percentResult = $userHours / $hundredPercent  * 100 / 100;
            return round($this->percentResult * 100, 2);
        } else {
            return $this->percentResult = 0;
        }
    }

    public function userTimeSwitcherButton(Request $request) {
        try {
            $response  = new Response();
            $key = $request->getPost('key');
            $day = $request->getPost('day');
            $month = $request->getPost('month');
            $year = $request->getPost('year');

            // get daytime id
            $getTimeDimensionId = TimeDimension::findFirst([
                'conditions' => 'day = :day: AND month = :month: AND year = :year:',
                'bind' => [
                    'day'     => $day,
                    'month'   => $month,
                    'year'   => $year,
                ]
            ]);

            $workHour = UserWorkDay::findFirst([
                'conditions' => 'user_id = :user_id: AND day = :day: AND month = :month: AND year = :year:',
                'bind' => [
                    'user_id' => $this->session->get('AUTH_ID'),
                    'day'     => $day,
                    'month'   => $month,
                    'year'    => $year,
                ]
            ]);

            if(!$workHour) {
                $createWorkDay = new UserWorkDay();

                $createWorkDay->day = $day;
                $createWorkDay->month = $month;
                $createWorkDay->year = $year;
                $createWorkDay->user_id = $this->session->get('AUTH_ID');
                $createWorkDay->time_dimension_id = $getTimeDimensionId->id;
                $createWorkDay->create();
            }

            if($request->getPost('start') == 'старт') {

                $check = self::checkIfUserComeOnTime($key);

                $workHour->start_time = $key;
                $workHour->update();

                if($check == false) {
                    $late = new UserLate();
                    $late->setDay($day);
                    $late->setMonth($month);
                    $late->setYear($year);
                    $late->setMonthName('null');
                    $late->setUserId($this->session->get('AUTH_ID'));
                    $late->create();
                }

                $response->setStatusCode(200);
                $response->setJsonContent($workHour->start_time);
                $response->send();
                exit;
            }

            if($request->getPost('stop') == 'стоп') {
                $workHour->end_time = $key;
                $workHour->update();

                $response->setStatusCode(200);
                $response->setJsonContent($workHour->end_time);
                $response->send();
                exit;
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function selectYearInWorkTable() {
        return DateDTO::getYears();
    }

    public function calculateUserLate() {
        $inThisMonth = new DateDTO();

        $total = UserLate::find([
            'conditions' => 'month = :month: and year = :year: and user_id = :user_id:',
            'bind' => [
                'month'   => $inThisMonth->getMonth(),
                'year'    => $inThisMonth->getYear(),
                'user_id' => $this->session->get('AUTH_ID')
            ]
        ]);
        $cntArray = array();

        foreach ($total as $it) {
            $cntArray[] = [$it->getDay()];
        }
        return count($cntArray);
    }

}