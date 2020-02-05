<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.02.2020
 * Time: 17:54
 */

namespace App\Forms;

use Dates\DTO\DateDTO;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Form;


class AdminGetDatesTableForm extends Form
{
    public function initialize()
    {
        $month = range(1, 12);

        $years = DateDTO::getYears();
        $res = array_combine ($years, $years);
        $monthRes = array_combine($month, $month);

        $chosenYear = new Select('yearTable', $res, [
            'using' =>  ['id', 'year'],
            'useEmpty'   => true,
            'emptyText'  => 'Выбрать год',
            'emptyValue' => '',
            "class"      => "custom-select"
        ]);

        $chosenMonth = new Select('monthTable', $monthRes, [
            'using' => array('id', 'name'),
            'useEmpty'   => true,
            'emptyText'  => 'Выбрать месяц',
            'emptyValue' => '',
            "class"      => "custom-select"
        ]);

        $submit = new Submit('submit', [
            "value" => "выбрать дату",
            "class" => "btn btn-primary",
        ]);

        $this->add($chosenYear);
        $this->add($chosenMonth);
        $this->add($submit);
    }
}