<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use App\Forms\AdminGetDatesTableForm;
use App\Forms\DeactivateUserForm;
use App\Forms\RegisterForm;
use App\Forms\RemoveFromLateForm;
use App\Forms\StartHourForm;
use Dates\DTO\DateDTO;
use Timetracker\Models\Users;
use Phalcon\Http\Request;
use Timetracker\Services\AdminService;

class AdminController extends \Phalcon\Mvc\Controller
{
    public $loginForm;
    public $tableForm;
    public $usersModel;
    public $adminService;
    public $deactivateForm;
    public $adminSetHourForm;
    public $removeForm;
    public $user;

    public function onConstruct() {}

    public function initialize()
    {
        $this->usersModel = new Users();
        $this->deactivateForm = new DeactivateUserForm();
        $this->adminService = new AdminService();
        $this->adminSetHourForm =  new StartHourForm();
        $this->tableForm =  new AdminGetDatesTableForm();
        $this->removeForm = new RemoveFromLateForm();
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

        $request = new Request();
        $dates   = new DateDTO();

        if ($request->isPost()) {
            if($request->isAjax()) {
                $this->adminService->editUserTime($request);
            }
        }

        $this->view->dayOfMonth = $dates->getDay();
        $this->view->data =  $this->adminService->getUserWorkDay($request);
        $this->view->users =  $this->adminService->getUsers();
        $this->view->form = $this->tableForm;
    }

    public function makeHolidayAction() {
        $request = new Request();
        if ($request->isPost()) {
            if($request->isAjax()) {
                $this->adminService->getMonthFromYear($request);
            }
        }

        $this->view->years = $this->adminService->getYears();
        $this->view->currMonthHolidays =  $this->adminService->currentMonthHoliday();
    }

    public function startDayHourAction() {

        $request = new Request();
        $time    = $this->request->getPost('time');

        if ($request->isPost()) {
            $this->adminService->makeStartWorkHourDay($time);
        }

        $this->view->form = new StartHourForm();

    }

    public function listOfLateUsersAction() {

        $request = new Request();
        if($request->isPost()) {
            $this->adminService->removeFromLate($request);
        }

        $this->view->form = $this->removeForm;
        $this->view->list = $this->adminService->listOfLateUsers();
    }
}