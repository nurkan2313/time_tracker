<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;


class StartHourForm extends Form {
    public function initialize()
    {

        $time = new Text('time', [
            "class" => "form-control",
            "placeholder" => "Введите время"
        ]);

        $submit = new Submit('submit', [
            "value" => "назначить время",
            "class" => "btn btn-primary",
        ]);

        $this->add($time);
        $this->add($submit);
    }

}