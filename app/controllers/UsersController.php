<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

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
    public $user;
    public $timeDimension;
    public $userWorkDay;

    public function onConstruct()
    {
    }

    public function initialize()
    {
        $this->loginForm     = new LoginForm();
        $this->user          = new Users();
        $this->timeDimension = new TimeDimension();
        $this->userWorkDay   = new UserWorkDay();
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Authentication
     */
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

        $daysArray = array();
        $userIdArray = array();

        $users = $this->user->find();

        /////////// add to other method
        foreach ($users as $id) {
            array_push($userIdArray, $id->getId());
        }

        $calendar = $this->timeDimension->find( [
            'conditions' => 'year = :year: and month = :month:',
            'bind'       => [
                'year' => $dates->getYear(),
                'month' => $dates->getMonth(),
            ]
        ]);

        foreach ($calendar as $cal )
        {
            array_push($daysArray, $cal->day);
        }

        array_unshift($daysArray, "день");
        ////////////////////////////////

        $userWorkDays = $userService->getUserWorkDay();

        if ($request->isPost()) {
            if($request->isAjax()) {
                // начать или остановить рабочий день
                $this->userTimeSwitcherButton($request);
            }
        }

        $this->view->dayOfMonth = $dates->getDay();
        $this->view->userId = $this->session->get('AUTH_ID');
        $this->view->days = $daysArray;
        $this->view->data = $userWorkDays;
    }

    public function userTimeSwitcherButton(Request $request) {
        try {

            $key = $request->getPost('key');
            $day = $request->getPost('day');

            $workHour = $this->userWorkDay->findFirst([
                'conditions' => 'user_id = :user_id: AND ' . ' day = :day:',
                'bind' => [
                    'user_id' => $this->session->get('AUTH_ID'),
                    'day'     => $day
                ]
            ]);

            if($request->getPost('start') == 'старт') {
                $workHour->start_time = $key;
                $workHour->update();
                return $workHour->start_time;
            }

            if($request->getPost('stop') == 'стоп') {
                $workHour->end_time = $key;
                $workHour->update();
                return;
            }

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * User Logout
     */
    public function logoutAction()
    {
        # https://docs.phalconphp.com/en/3.3/session#remove-destroy

        // Destroy the whole session
        $this->session->destroy();
        return $this->response->redirect('user/login');
    }

    /**
     * Searches for Users
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\Timetracker\Models\Users', $_GET)->getParams();
        $parameters['order'] = "id";

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any Users");

            $this->dispatcher->forward([
                "controller" => "Users",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $users,
            'limit'=> 10,
            'page' => $numberPage,
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Edits a User
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user = Users::findFirstByid($id);
            if (!$user) {
                $this->flash->error("User was not found");

                $this->dispatcher->forward([
                    'controller' => "Users",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("name", $user->name);
            $this->tag->setDefault("login", $user->login);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("active", $user->active);
            
        }
    }

    /**
     * Creates a new User
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'index'
            ]);

            return;
        }

        $user = new Users();
        $user->name = $this->request->getPost("name", "int");
        $user->login = $this->request->getPost("login", "int");
        $user->password = $this->request->getPost("password", "int");
        $user->email = $this->request->getPost("email", "int");
        $user->active = $this->request->getPost("active", "int");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("User was created successfully");

        $this->dispatcher->forward([
            'controller' => "Users",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a User edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user = Users::findFirstByid($id);

        if (!$user) {
            $this->flash->error("User does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'index'
            ]);

            return;
        }

        $user->name = $this->request->getPost("name", "int");
        $user->login = $this->request->getPost("login", "int");
        $user->password = $this->request->getPost("password", "int");
        $user->email = $this->request->getPost("email", "int");
        $user->active = $this->request->getPost("active", "int");
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'edit',
                'params' => [$user->id]
            ]);

            return;
        }

        $this->flash->success("User was updated successfully");

        $this->dispatcher->forward([
            'controller' => "Users",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a User
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("User was not found");

            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "Users",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("User was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "Users",
            'action' => "index"
        ]);
    }
}
