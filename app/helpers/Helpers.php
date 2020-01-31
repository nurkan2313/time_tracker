<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.01.2020
 * Time: 22:21
 */

namespace Timetracker\Helper;


use Timetracker\Models\Users;

class Helpers
{
    public static function group_by($key, $data, $daysArray) {
        $res = array();
        $users = Users::find();
        $usersArray = array();

        foreach ($users as $user) {
            array_push($usersArray, $user->getName());
        }

        foreach($daysArray as $cnt) {
            foreach($data as $key => $day) {
                if ($day['day'] == $cnt)
                {
                    $res[$day['day']][] = $day;
                }
            }
        }

        return $res;
    }
}