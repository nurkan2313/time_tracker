<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.01.2020
 * Time: 9:21
 */

namespace Timetracker\Controllers;

class ErrorsController extends ControllerBase
{

    public function route401Action() {
        echo 'not allowed';
    }

    public function show500Action() {
        echo 'error 500';
    }

    public function show404Action() {
        echo '404';
    }
}