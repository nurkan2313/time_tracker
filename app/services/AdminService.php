<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.02.2020
 * Time: 14:16
 */

namespace Timetracker\Services;

use Dates\DTO\DateDTO;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Timetracker\Models\StartWorkHour;
use Timetracker\Models\TimeDimension;
use Timetracker\Models\Users;
use Timetracker\Models\UserWorkDay;

class AdminService extends Injectable
{
    public $timeDimension;
    public $users;

    public function initialize()
    {
        $this->timeDimension = new TimeDimension();
        $this->users = new Users();
    }

    public function getUserWorkDay(): array {

        $dates      = new DateDTO();
        $daysArray  = array();
        $usersArray = array();

        try {
            $calendar = TimeDimension::find( [
                'conditions' => 'year = :year: and month = :month:',
                'bind'       => [
                    'year' => $dates->getYear(),
                    'month' => $dates->getMonth(),
                ]
            ]);

            foreach ($calendar as $cal )
            {
                $daysArray[] = [
                    'day' => $cal->day,
                    'month' => $cal->month,
                    'year' => $cal->year
                ];

            }

            $users =  Users::find();

            foreach ($users as $user) {
                array_push($usersArray, $user->getId());
            }

            $res = array();

            foreach ($daysArray as $day) {
                foreach ($usersArray as $usr) {

                    $query = $this->modelsManager->createQuery("
                      SELECT DISTINCT 
                          IFNULL( (SELECT Timetracker\Models\UserWorkDay.start_time
                                       FROM Timetracker\Models\UserWorkDay
                                       WHERE Timetracker\Models\UserWorkDay.user_id = ".$usr." 
                                       and Timetracker\Models\UserWorkDay.day = ".$day['day']." 
                                       and Timetracker\Models\UserWorkDay.month = ".$day['month']."
                                       and Timetracker\Models\UserWorkDay.year = ".$day['year']."), 0) as start_time,
                          IFNULL( (SELECT Timetracker\Models\UserWorkDay.end_time  
                                       FROM Timetracker\Models\UserWorkDay 
                                       WHERE Timetracker\Models\UserWorkDay.user_id = ".$usr." 
                                        and Timetracker\Models\UserWorkDay.day = " .$day['day'] ."
                                        and Timetracker\Models\UserWorkDay.month = ".$day['month']."
                                        and Timetracker\Models\UserWorkDay.year = ".$day['year']."), 0) as end_time,
                          IFNULL( (SELECT  Timetracker\Models\Users.name 
                                        FROM Timetracker\Models\Users
                                        WHERE Timetracker\Models\Users.id =  ".$usr." ),0) as name
                      FROM Timetracker\Models\UserWorkDay ")->execute();

                    foreach ($query as $item) {
                        $res[$day['day']][] = [
                            'name' => $item->name,
                            'start_time' => $item->start_time,
                            'end_time' => $item->end_time,
                            'id' => $usr,
                            'day' => $day['day'],
                            'year' => $day['year'],
                            'month' => $day['month']
                        ];
                    }

                }
            }

            return $res;

        } catch (\Exception $e){
            print_r($e->getMessage());
        }
    }

    public function getUsers() {
        $userBuilder = $this->modelsManager->createBuilder();
        $userBuilder->columns(['Timetracker\Models\Users.name as name'])
            ->from('Timetracker\Models\Users')
            ->orderBy('Timetracker\Models\Users.id');
        $data = $userBuilder->getQuery()->execute();
        return $data;
    }

    public function editUserTime(Request $request) {
        try {

            $key = $request->getPost('key');
            $day = $request->getPost('day');
            $month = $request->getPost('month');
            $year = $request->getPost('year');
            $user_id = $request->getPost('id');

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
                    'user_id' => $user_id,
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
                $createWorkDay->user_id = $user_id;
                $createWorkDay->time_dimension_id = $getTimeDimensionId->id;
                $createWorkDay->create();
            }

            if($request->getPost('start') == 'старт') {
                $workHour->start_time = $key;
                $workHour->update();
                exit;
            }

            if($request->getPost('stop') == 'стоп') {
                $workHour->end_time = $key;
                $workHour->update();
                exit;
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function currentMonthHoliday() {

        $dates = new DateDTO();
        $holidayArray = array();

        $currentMonthHolidays = TimeDimension::find([
            'conditions' => 'year = :year: and month = :month: and holiday_flag = :holiday:',
            'bind'       => [
                'year' => $dates->getYear(),
                'month' => $dates->getMonth(),
                'holiday' => 't'
            ]
        ]);

        foreach ($currentMonthHolidays as $holiday) {
            $holidayArray[$holiday->year][] = [
                'month' => $holiday->month,
                'day'   => $holiday->day
            ];
        }

        return $holidayArray;
    }

    public function getYears () {
       return DateDTO::getYears();
    }

    public function getMonthFromYear(Request $request) {
        $response  = new Response();
        $year      = $request->getPost('year');
        $month     = $request->getPost('month');
        $workMonth = $request->getPost('work');
        $updateDay = $request->getPost('updateDay');
        $holiday_month = $request->getPost('holiday_month');

        //Посмотреть нерабочие дни
        if($year !== '' and $month == '') {
            $monthArray = array();
            $currentMonthHolidays = TimeDimension::find([
                'conditions' => 'year = :year:',
                'columns'    => 'distinct month_name',
                'bind'       => [
                    'year' => $year,
                ],
                'order' => 'month ASC'
            ]);

            foreach ($currentMonthHolidays as $holiday) {
                array_push($monthArray, $holiday->month_name);
            }


            $response->setStatusCode(200);
            $response->setJsonContent($monthArray);
            $response->send();
            exit;
        }

        if($year !== '' and $month !== '' and $workMonth == 'false') {

            $daysArray = array();
            $days = TimeDimension::find([
                'conditions' => 'year = :year: and month_name = :month_name: and holiday_flag = :holiday_flag:',
                'columns'    => 'day',
                'bind'       => [
                    'year' => $year,
                    'month_name' => $month,
                    'holiday_flag' => 't'
                ],
                'order' => 'day ASC'
            ]);


            foreach ($days as $holiday) {
                array_push($daysArray, $holiday->day);
            }

            $response->setStatusCode(200);
            $response->setJsonContent($daysArray);
            $response->send();
            exit;
        }

        //Создать нерабочие дни
        if($year !== '' and $month == '' and $workMonth == 'true') {

            $monthArray = array();
            $days = TimeDimension::find([
                'conditions' => 'year = :year:',
                'columns'    => 'distinct month_name',
                'bind'       => [
                    'year' => $year,
                ],
                'order' => 'month ASC'
            ]);

            foreach ($days as $holiday) {
                array_push($monthArray, $holiday->month_name);
            }

            $response->setStatusCode(200);
            $response->setJsonContent($monthArray);
            $response->send();
            exit;
        }

        if($year !== '' and $month !== '' and $workMonth == 'true') {

            $workDays = array();
            $days = TimeDimension::find([
                'conditions' => 'year = :year: and month_name = :month_name: and holiday_flag = :holiday_flag: and weekend_flag = :weekend_flag:',
                'columns'    => 'day',
                'bind'       => [
                    'year' => $year,
                    'month_name' => $month,
                    'holiday_flag' => 'f',
                    'weekend_flag' => 'f'
                ],
                'order' => 'day ASC'
            ]);


            foreach ($days as $holiday) {
                array_push($workDays, $holiday->day);
            }


            $response->setStatusCode(200);
            $response->setJsonContent($workDays);
            $response->send();
            exit;
        }

        if($year == '' and $month == '' and $workMonth == 'false') {

            foreach ($updateDay as $day) {
               $this->modelsManager->createQuery("UPDATE Timetracker\Models\TimeDimension SET holiday_flag = 't' WHERE day = ".$day." and month_name = '" .$holiday_month. "' ")->execute();
            }

            $test = 'OK';
            $response->setStatusCode(200);
            $response->setJsonContent($test);
            $response->send();
            exit;
        }
    }

    public function selectHolidayFromMonth(Request $request) {

        $month = $request->getPost('month');
        $year = $request->getPost('year');

        $holidayArray = array();

        $currentMonthHolidays = TimeDimension::find([
            'conditions' => 'year = :year: and month = :month: and holiday_flag = :holiday:',
            'bind'       => [
                'year' => $year,
                'month' => $month,
                'holiday' => 't'
            ]
        ]);

        foreach ($currentMonthHolidays as $holiday) {
            $holidayArray[$holiday->year][] = [
                'day'   => $holiday->month
            ];
        }

        return $holidayArray;
    }

    public function makeStartWorkHourDay($time) {
        $findTime = StartWorkHour::findFirst(1);
        $findTime->setTime($time);
        $findTime->save();
    }
}