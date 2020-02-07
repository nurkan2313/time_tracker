<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.02.2020
 * Time: 17:02
 */

namespace Timetracker\Services;


use Dates\DTO\DateDTO;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Timetracker\Models\TimeDimension;
use Timetracker\Models\Users;
use Timetracker\Models\UserWorkDay;

class DrawTableService extends Injectable implements IDrawTable
{
    public $year;
    public $month;
    public $days;
    public $calendar;

    public function initialize()
    {
        $this->calendar = new TimeDimension();
    }

    public function draw(Request $request)
    {
        $dates   = new DateDTO();
        $daysArray = array();
        $usersArray = array();
        $allCurrentDays = array();

        try {

            if($request->isPost()) {
                $this->year  = $this->request->getPost('yearTable');
                $this->month = $this->request->getPost('monthTable');
            } else {
                $this->year  = $dates->getYear();
                $this->month =  $dates->getMonth();
            }

            $this->calendar = TimeDimension::find( [
                'conditions' => 'year = :year: and month = :month:',
                'bind'       => [
                    'year' =>  $this->year,
                    'month' => $this->month,
                ]
            ]);

            foreach ($this->calendar as $cal )
            {
                $daysArray[] = [
                    'day' => $cal->day,
                    'month' => $cal->month,
                    'year' => $cal->year
                ];

                $allCurrentDays[] = [
                    'day' => $cal->day,
                ];
            }

            $this->days = $allCurrentDays;
            $users =  Users::find();

            foreach ($users as $user) {
                $usersArray[] = [ 'id' => $user->getId(), 'name' => $user->getName()];
            }

            $res = array();

            foreach ($daysArray as $day) {
                foreach ($usersArray as $usr) {

                    $query = UserWorkDay::find([
                        'conditions'=>'day = :day: and month = :month: and year = :year: and user_id = :user_id:',
                        'columns' => 'IFNULL(MIN(day), 0) day,
                                      IFNULL(MIN(start_time), \'0\') start_time, 
                                      IFNULL(MIN(end_time), \'0\') end_time 
                                      ',
                        'bind'=>[
                            'day' => $day['day'],
                            'month' => $day['month'],
                            'year' => $day['year'],
                            'user_id' => $usr['id']
                        ]
                    ]);

                    foreach ($query as $item) {
                        $res[$day['day']][] = [
                            "day" => $day['day'],
                            'start_time' => $item->start_time,
                            'end_time' => $item->end_time,
                            'month' => $day['month'],
                            'id' => $usr['id']
                        ];
                    }
                }
            }

            return $res;

        } catch (\Exception $e){
            print_r($e->getMessage());
        }
    }
}