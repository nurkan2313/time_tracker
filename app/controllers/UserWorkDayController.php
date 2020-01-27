<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Timetracker\Models\UserWorkDay;

class UserWorkDayController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for user_work_day
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\Timetracker\Models\UserWorkDay', $_GET)->getParams();
        $parameters['order'] = "id";

        $user_work_day = UserWorkDay::find($parameters);
        if (count($user_work_day) == 0) {
            $this->flash->notice("The search did not find any user_work_day");

            $this->dispatcher->forward([
                "controller" => "user_work_day",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $user_work_day,
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
     * Edits a user_work_day
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user_work_day = UserWorkDay::findFirstByid($id);
            if (!$user_work_day) {
                $this->flash->error("user_work_day was not found");

                $this->dispatcher->forward([
                    'controller' => "user_work_day",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user_work_day->id;

            $this->tag->setDefault("id", $user_work_day->id);
            $this->tag->setDefault("total_work_hour", $user_work_day->total_work_hour);
            $this->tag->setDefault("remain", $user_work_day->remain);
            $this->tag->setDefault("day", $user_work_day->day);
            $this->tag->setDefault("start_time", $user_work_day->start_time);
            $this->tag->setDefault("end_time", $user_work_day->end_time);
            $this->tag->setDefault("user_id", $user_work_day->user_id);
            $this->tag->setDefault("time_dimension_id", $user_work_day->time_dimension_id);
            
        }
    }

    /**
     * Creates a new user_work_day
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'index'
            ]);

            return;
        }

        $user_work_day = new UserWorkDay();
        $user_work_day->id = $this->request->getPost("id", "int");
        $user_work_day->totalWorkHour = $this->request->getPost("total_work_hour", "int");
        $user_work_day->remain = $this->request->getPost("remain", "int");
        $user_work_day->day = $this->request->getPost("day", "int");
        $user_work_day->startTime = $this->request->getPost("start_time", "int");
        $user_work_day->endTime = $this->request->getPost("end_time", "int");
        $user_work_day->userId = $this->request->getPost("user_id", "int");
        $user_work_day->timeDimensionId = $this->request->getPost("time_dimension_id", "int");
        

        if (!$user_work_day->save()) {
            foreach ($user_work_day->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user_work_day was created successfully");

        $this->dispatcher->forward([
            'controller' => "user_work_day",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user_work_day edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user_work_day = UserWorkDay::findFirstByid($id);

        if (!$user_work_day) {
            $this->flash->error("user_work_day does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'index'
            ]);

            return;
        }

        $user_work_day->id = $this->request->getPost("id", "int");
        $user_work_day->totalWorkHour = $this->request->getPost("total_work_hour", "int");
        $user_work_day->remain = $this->request->getPost("remain", "int");
        $user_work_day->day = $this->request->getPost("day", "int");
        $user_work_day->startTime = $this->request->getPost("start_time", "int");
        $user_work_day->endTime = $this->request->getPost("end_time", "int");
        $user_work_day->userId = $this->request->getPost("user_id", "int");
        $user_work_day->timeDimensionId = $this->request->getPost("time_dimension_id", "int");
        

        if (!$user_work_day->save()) {

            foreach ($user_work_day->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'edit',
                'params' => [$user_work_day->id]
            ]);

            return;
        }

        $this->flash->success("user_work_day was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user_work_day",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user_work_day
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user_work_day = UserWorkDay::findFirstByid($id);
        if (!$user_work_day) {
            $this->flash->error("user_work_day was not found");

            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user_work_day->delete()) {

            foreach ($user_work_day->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_work_day",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user_work_day was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user_work_day",
            'action' => "index"
        ]);
    }
}
