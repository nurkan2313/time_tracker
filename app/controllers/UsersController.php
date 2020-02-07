<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use App\Forms\UserGetDatesForWorkTableForm;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Http\Request;

use Timetracker\Helper\Helpers;
use Timetracker\Models\TimeDimension;
use Timetracker\Models\Users as Users;

use App\Forms\LoginForm;
use Timetracker\Models\UserWorkDay;
use Dates\DTO\DateDTO;
use Timetracker\Services\UsersService;

class UsersController extends ControllerBase
{
    public $loginForm;
    public $tableDateForm;
    public $user;
    public $timeDimension;
    public $userWorkDay;

    const PARTIAL_USER = 'USER';

    public function onConstruct()
    {
    }

    public function initialize()
    {
        $this->loginForm     = new LoginForm();
        $this->tableDateForm = new UserGetDatesForWorkTableForm();
        $this->user          = new Users();
        $this->timeDimension = new TimeDimension();
        $this->userWorkDay   = new UserWorkDay();
    }

    public function indexAction()
    {
        //
    }

    public function loginAction() {
        /**
         * @setTitle()
         * @prependTitle()
         */
        $this->tag->setTitle('Phalcon::Login');
        $this->view->form = new LoginForm();
    }

    public function loginSubmitAction() {

        if(!$this->request->isPost()) {
            return $this->request->redirect('user/login');
        }

        $this->loginForm->bind($_POST, $this->user);

        if(!$this->loginForm->isValid()) {
            foreach ($this->loginForm->getMessages() as $message) {
                $this->flashSession->error($message);
                $this->dispatcher->forward([
                   'controller' => $this->router->getControllerName(),
                    'action' => 'login'
                ]);
                return;
            }
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->user->findFirst([
            'email = :email:',
            'bind' => [
                'email' => $email,
            ]
        ]);

        if ($user->getActive() != 1) {
            $this->flashSession->error("User Deactivate");
            return $this->response->redirect('user/login');
        }

        if ($user) {
            if ($this->security->checkHash($password, $user->password))
            {
                // Set a session
                if($user->getRole() == 'admin') {
                    $this->session->set('AUTH', 'admin');
                } else {
                    $this->session->set('AUTH', 'users');
                }

                $this->session->set('AUTH_ID', $user->getId());
                $this->session->set('AUTH_NAME', $user->getName());
                $this->session->set('AUTH_EMAIL', $user->getEmail());
                $this->session->set('IS_LOGIN', 1);

                // $this->flashSession->success("Login Success");
                return $this->response->redirect('user/profile');
            }
        } else {
            $this->security->hash(rand());
        }

        return $this->response->redirect('/');

    }

    public function profileAction(){}

    public function workTableAction() {
        $userService = new UsersService();
        $request = new Request();
        $dates   = new DateDTO();

        if ($request->isPost()) {
            if($request->isAjax()) {
                $userService->userTimeSwitcherButton($request);
            }
        }

        $this->view->lates      = $userService->calculateUserLate();
        $this->view->form       = $this->tableDateForm;
        $this->view->tableYear  = $userService->selectYearInWorkTable();
        $this->view->dayOfMonth = $dates->getDay();
        $this->view->monthTab   = $dates->getMonth();
        $this->view->userId     = $this->session->get('AUTH_ID');
        $this->view->users      = $userService->getUsers();
        $this->view->total      = $userService->calculateUserTotalHour();
        $this->view->totalHour  = $userService->totalHourPerMonth();
        $this->view->assigned   = $userService->calculateAssignedHour();
        $this->view->data       = $userService->getUserWorkDay($request);
        $this->view->days       = $userService->allCurrentMonthDaysArray();
        $this->view->partialUser = UsersController::PARTIAL_USER;
    }

    public function logoutAction()
    {
        # https://docs.phalconphp.com/en/3.3/session#remove-destroy

        // Destroy the whole session
        $this->session->destroy();
        return $this->response->redirect('user/login');
    }

}
