<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.01.2020
 * Time: 22:21
 */

namespace Timetracker\Helper;


class Helpers
{

    public static function group_by($key, $data) {
        $result = array();
        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }
        return $result;
    }
}