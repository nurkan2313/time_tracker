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

    /**
     * @param Request $request
     * @return float|mixed|string|void
     */
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
                return;
            }

            if($request->getPost('stop') == 'стоп') {
                $workHour->end_time = $key;
                $workHour->update();
                return;
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}