<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use App\Forms\RegisterForm;
use Timetracker\Models\Users;

class AdminController extends \Phalcon\Mvc\Controller
{
    public $loginForm;
    public $usersModel;

    public function onConstruct()
    {
    }

    public function initialize()
    {
        $this->usersModel = new Users();
    }

    public function indexAction()
    {

    }

    public function registerAction()
    {
        $this->tag->setTitle('Phalcon :: Register');
        $this->view->form = new RegisterForm();
    }

    public function registerSubmitAction()
    {
        $form = new RegisterForm();

        // check request
        if (!$this->request->isPost()) {
            return $this->response->redirect('user/register');
        }

        $form->bind($_POST, $this->usersModel);
        // check form validation

        if (!$form->isValid()) {
            foreach ($form->getMessages() as $message) {
                $this->flashSession->error($message);
                $this->dispatcher->forward([
                    'controller' => $this->router->getControllerName(),
                    'action' => 'register',
                ]);
                return;
            }
        }

        $this->usersModel->setPassword($this->security->hash($_POST['password']));
        $this->usersModel->setActive(1);

        if (!$this->usersModel->save()) {
            foreach ($this->usersModel->getMessages() as $m) {
                $this->flashSession->error($m);
                $this->dispatcher->forward([
                    'controller' => $this->router->getControllerName(),
                    'action' => 'register',
                ]);
                return;
            }
        }

        $this->flashSession->success('Thanks for registering!');
        return $this->response->redirect('user/login');
    }
}