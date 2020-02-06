<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.02.2020
 * Time: 12:47
 */

namespace App\Forms;


use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class RemoveFromLateForm extends Form
{
    public function initialize()
    {

        $user = new Text('table_id', [
            "class" => "form-control",
            "placeholder" => "введите id пользователя"
        ]);

        $submit = new Submit('submit', [
            "value" => "удалить",
            "class" => "btn btn-primary",
        ]);

        $this->add($user);
        $this->add($submit);
    }

}