<?php

namespace Timetracker\Helper;

class Helpers
{
    public static function group_by($key, $data) {
        $res = array();
            foreach($data as $val) {
                if(array_key_exists($key, $val)){
                    $result[$val[$key]][] = $val;
                }else{
                    $result[""][] = $val;
                }
            }
        return $res;
    }
}