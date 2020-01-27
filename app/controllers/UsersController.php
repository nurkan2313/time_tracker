<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Timetracker\Models\TimeDimension;
use Timetracker\Models\Users;
use App\Forms\LoginForm;
use Timetracker\Models\UserWorkDay;
use Dates\DTO\DateDTO;

class UsersController extends ControllerBase
{
    public $loginForm;
    public $user;

    public function onConstruct()
    {
    }

    public function initialize()
    {
        $this->loginForm = new LoginForm();
        $this->user = new Users();
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

        $user = Users::findFirst([
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

        $dates = new DateDTO();
        $calendar = TimeDimension::find(
            [
                'conditions' => 'year = :year: and month = :month:',
                'bind'       => [
                    'year' => $dates->getYear(),
                    'month' => $dates->getMonth(),
                ]
            ]
        );

        $workDay = UserWorkDay::find();
        $user = array();
        $calendarArrayToFillUp = array();

        foreach ($calendar as $cal )
        {
            $calendarArrayToFillUp[] = array(
                    'data' => [
                        'day' => $cal->day,
                        'month' => '',
                        'user' => $user,
                    ]
            );
        }

        foreach ($calendarArrayToFillUp as $key=>$fillUserData) {
            foreach ($fillUserData as $item) {
                foreach ($workDay as $work) {
                    if($work->day === $item['day']) {
                        $user_id = $work->user_id;
                        $userName = Users::findFirst($user_id)->getName();
                        $calendarArrayToFillUp[$key]['data']['user'][] = array(
                            'total_work_hour' => $work->total_work_hour,
                            'start_time' => $work->start_time,
                            'end_time' => $work->end_time,
                            'user_name' => $userName
                       );

                    }
                }
            }
        }

        $this->view->data = $calendarArrayToFillUp;
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
