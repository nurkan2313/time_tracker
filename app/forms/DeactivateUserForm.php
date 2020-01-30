<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.01.2020
 * Time: 13:17
 */

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;

class DeactivateUserForm extends Form
{
    public function initialize()
    {
        $userId = new Text('user_id', [
            "class" => "form-control",
            "placeholder" => "введите id пользователя"
        ]);

        $userId->addValidators([
            new PresenceOf(['message' => 'user id is required'])
        ]);

        $submit = new Submit('submit', [
            "value" => "Деактивировать",
            "class" => "btn btn-primary",
        ]);

        $this->add($userId);
        $this->add($submit);
    }

}