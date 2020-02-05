<?php
declare(strict_types=1);

 

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Timetracker\Models\UserLate;

class UserLateController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for user_late
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\Timetracker\Models\UserLate', $_GET)->getParams();
        $parameters['order'] = "id";

        $user_late = UserLate::find($parameters);
        if (count($user_late) == 0) {
            $this->flash->notice("The search did not find any user_late");

            $this->dispatcher->forward([
                "controller" => "user_late",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $user_late,
            'limit'=> 10,
            'page' => $numberPage,
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        //
    }

    /**
     * Edits a user_late
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user_late = UserLate::findFirstByid($id);
            if (!$user_late) {
                $this->flash->error("user_late was not found");

                $this->dispatcher->forward([
                    'controller' => "user_late",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user_late->getId();

            $this->tag->setDefault("id", $user_late->getId());
            $this->tag->setDefault("day", $user_late->getDay());
            $this->tag->setDefault("month", $user_late->getMonth());
            $this->tag->setDefault("month_name", $user_late->getMonthName());
            $this->tag->setDefault("year", $user_late->getYear());
            $this->tag->setDefault("user_id", $user_late->getUserId());
            
        }
    }

    /**
     * Creates a new user_late
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'index'
            ]);

            return;
        }

        $user_late = new UserLate();
        $user_late->setday($this->request->getPost("day", "int"));
        $user_late->setmonth($this->request->getPost("month", "int"));
        $user_late->setmonthName($this->request->getPost("month_name", "int"));
        $user_late->setyear($this->request->getPost("year", "int"));
        $user_late->setuserId($this->request->getPost("user_id", "int"));
        

        if (!$user_late->save()) {
            foreach ($user_late->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user_late was created successfully");

        $this->dispatcher->forward([
            'controller' => "user_late",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user_late edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user_late = UserLate::findFirstByid($id);

        if (!$user_late) {
            $this->flash->error("user_late does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'index'
            ]);

            return;
        }

        $user_late->setday($this->request->getPost("day", "int"));
        $user_late->setmonth($this->request->getPost("month", "int"));
        $user_late->setmonthName($this->request->getPost("month_name", "int"));
        $user_late->setyear($this->request->getPost("year", "int"));
        $user_late->setuserId($this->request->getPost("user_id", "int"));
        

        if (!$user_late->save()) {

            foreach ($user_late->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'edit',
                'params' => [$user_late->getId()]
            ]);

            return;
        }

        $this->flash->success("user_late was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user_late",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user_late
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user_late = UserLate::findFirstByid($id);
        if (!$user_late) {
            $this->flash->error("user_late was not found");

            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user_late->delete()) {

            foreach ($user_late->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_late",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user_late was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user_late",
            'action' => "index"
        ]);
    }
}
