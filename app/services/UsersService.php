<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.01.2020
 * Time: 10:24
 */

namespace Timetracker\Services;
use Dates\DTO\DateDTO;
use Phalcon\Di\Injectable;
use Timetracker\Helper\Helpers;
use Timetracker\Models\TimeDimension;

class UsersService extends Injectable
{
    public $timeDimension;
    public $percentResult;
    public function initialize()
    {
        $this->timeDimension = new TimeDimension();
    }

    public function getUserWorkDay(): array {

        $dates   = new DateDTO();
        $daysArray = array();

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
                array_push($daysArray, $cal->day);
            }

            $builder = $this->modelsManager->createBuilder();
            $builder
                ->columns(['Timetracker\Models\Users.id as id, Timetracker\Models\Users.name as name, 
                    Timetracker\Models\UserWorkDay.total_work_hour as total_work_hour,
                     Timetracker\Models\UserWorkDay.day as day, Timetracker\Models\UserWorkDay.start_time as start_time,
                      Timetracker\Models\UserWorkDay.end_time as end_time, Timetracker\Models\UserWorkDay.user_id as user_id'])
                ->from('Timetracker\Models\UserWorkDay')
                ->innerJoin('Timetracker\Models\Users', 'Timetracker\Models\UserWorkDay.user_id = Timetracker\Models\Users.id')
                ->orderBy('Timetracker\Models\Users.id');

            $data = $builder->getQuery()->execute();

            $userWorkDays = Helpers::group_by('name', $data->toArray(), $daysArray);

            return $userWorkDays;

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

    public function calculateUserTotalHour() {
        $total = 0;

        $userBuilder = $this->modelsManager->createBuilder();
        $userBuilder->columns(['Timetracker\Models\UserWorkDay.start_time as start_time, Timetracker\Models\UserWorkDay.end_time as end_time'])
            ->from('Timetracker\Models\UserWorkDay')
            ->where('Timetracker\Models\UserWorkDay.user_id =' . $this->session->get('AUTH_ID'))
            ->orderBy('Timetracker\Models\UserWorkDay.user_id');
        $data = $userBuilder->getQuery()->execute();

        foreach ($data as $calculateUserStat) {
            $total += ($calculateUserStat->end_time - $calculateUserStat->start_time);
            echo $total;
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
            $this->percentResult = ( $userHours / $hundredPercent ) * 100;
            return $this->percentResult;
        } else {
            return 'Вы еще не начали работу в этом месяце';
        }

    }

    public function dayDeadline() {
        
    }
}