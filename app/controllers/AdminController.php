<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use App\Forms\DeactivateUserForm;
use App\Forms\RegisterForm;
use Dates\DTO\DateDTO;
use Timetracker\Models\Users;
use Phalcon\Http\Request;
use Timetracker\Services\AdminService;

class AdminController extends \Phalcon\Mvc\Controller
{
    public $loginForm;
    public $usersModel;
    public $deactivateForm;
    public $user;

    public function onConstruct() {}

    public function initialize()
    {
        $this->usersModel = new Users();
        $this->deactivateForm = new DeactivateUserForm();
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

    public function disableUserAction() {

        if($this->request->isPost()) {

            $this->deactivateForm->bind($_POST, $this->usersModel);

            if(!$this->deactivateForm->isValid()) {
                foreach ($this->deactivateForm->getMessages() as $message) {
                    $this->flashSession->error($message);
                }
            }

            try {

                $this->user = $this->usersModel->findFirst([
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $this->request->getPost('user_id'),
                    ]
                ]);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            $this->user->setActive(0);
            $this->user->update();
            $this->flashSession->message('success', 'user ' . $this->request->getPost('user_id').  ' deactivated');
        }

        $allUsers = $this->usersModel->find();
        $this->view->users = $allUsers;
        $this->view->form = $this->deactivateForm;
    }

    public function usersManagementAction() {
        $adminService = new AdminService();
        $request = new Request();
        $dates   = new DateDTO();

        $this->view->dayOfMonth = $dates->getDay();
        $this->view->usersTable = $adminService->getUserWorkDay();
        $this->view->users = $adminService->getUsers();
    }
}