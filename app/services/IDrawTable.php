<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.02.2020
 * Time: 16:38
 */

namespace Timetracker\Services;


use Phalcon\Http\Request;

interface IDrawTable
{
    public function draw(Request $request);
}