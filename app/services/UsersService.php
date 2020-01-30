<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.01.2020
 * Time: 10:24
 */

namespace Timetracker\Services;
use Phalcon\Di\Injectable;
use Timetracker\Helper\Helpers;

class UsersService extends Injectable
{
    public function getUserWorkDay() {

        try {

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

            $userWorkDays = Helpers::group_by('name', $data->toArray());

            return $userWorkDays;

        } catch (\Exception $e){
            print_r($e->getMessage());
        }
        return;
    }
}