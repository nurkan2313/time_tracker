<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Timetracker\Models\TimeDimension;

class TimeDimensionController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for time_dimension
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\Timetracker\Models\TimeDimension', $_GET)->getParams();
        $parameters['order'] = "id";

        $time_dimension = TimeDimension::find($parameters);
        if (count($time_dimension) == 0) {
            $this->flash->notice("The search did not find any time_dimension");

            $this->dispatcher->forward([
                "controller" => "time_dimension",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $time_dimension,
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
     * Edits a time_dimension
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $time_dimension = TimeDimension::findFirstByid($id);
            if (!$time_dimension) {
                $this->flash->error("time_dimension was not found");

                $this->dispatcher->forward([
                    'controller' => "time_dimension",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $time_dimension->id;

            $this->tag->setDefault("id", $time_dimension->id);
            $this->tag->setDefault("db_date", $time_dimension->db_date);
            $this->tag->setDefault("year", $time_dimension->year);
            $this->tag->setDefault("month", $time_dimension->month);
            $this->tag->setDefault("day", $time_dimension->day);
            $this->tag->setDefault("quarter", $time_dimension->quarter);
            $this->tag->setDefault("week", $time_dimension->week);
            $this->tag->setDefault("day_name", $time_dimension->day_name);
            $this->tag->setDefault("month_name", $time_dimension->month_name);
            $this->tag->setDefault("holiday_flag", $time_dimension->holiday_flag);
            $this->tag->setDefault("weekend_flag", $time_dimension->weekend_flag);
            $this->tag->setDefault("event", $time_dimension->event);
            
        }
    }

    /**
     * Creates a new time_dimension
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'index'
            ]);

            return;
        }

        $time_dimension = new TimeDimension();
        $time_dimension->id = $this->request->getPost("id", "int");
        $time_dimension->dbDate = $this->request->getPost("db_date", "int");
        $time_dimension->year = $this->request->getPost("year", "int");
        $time_dimension->month = $this->request->getPost("month", "int");
        $time_dimension->day = $this->request->getPost("day", "int");
        $time_dimension->quarter = $this->request->getPost("quarter", "int");
        $time_dimension->week = $this->request->getPost("week", "int");
        $time_dimension->dayName = $this->request->getPost("day_name", "int");
        $time_dimension->monthName = $this->request->getPost("month_name", "int");
        $time_dimension->holidayFlag = $this->request->getPost("holiday_flag", "int");
        $time_dimension->weekendFlag = $this->request->getPost("weekend_flag", "int");
        $time_dimension->event = $this->request->getPost("event", "int");
        

        if (!$time_dimension->save()) {
            foreach ($time_dimension->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("time_dimension was created successfully");

        $this->dispatcher->forward([
            'controller' => "time_dimension",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a time_dimension edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $time_dimension = TimeDimension::findFirstByid($id);

        if (!$time_dimension) {
            $this->flash->error("time_dimension does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'index'
            ]);

            return;
        }

        $time_dimension->id = $this->request->getPost("id", "int");
        $time_dimension->dbDate = $this->request->getPost("db_date", "int");
        $time_dimension->year = $this->request->getPost("year", "int");
        $time_dimension->month = $this->request->getPost("month", "int");
        $time_dimension->day = $this->request->getPost("day", "int");
        $time_dimension->quarter = $this->request->getPost("quarter", "int");
        $time_dimension->week = $this->request->getPost("week", "int");
        $time_dimension->dayName = $this->request->getPost("day_name", "int");
        $time_dimension->monthName = $this->request->getPost("month_name", "int");
        $time_dimension->holidayFlag = $this->request->getPost("holiday_flag", "int");
        $time_dimension->weekendFlag = $this->request->getPost("weekend_flag", "int");
        $time_dimension->event = $this->request->getPost("event", "int");
        

        if (!$time_dimension->save()) {

            foreach ($time_dimension->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'edit',
                'params' => [$time_dimension->id]
            ]);

            return;
        }

        $this->flash->success("time_dimension was updated successfully");

        $this->dispatcher->forward([
            'controller' => "time_dimension",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a time_dimension
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $time_dimension = TimeDimension::findFirstByid($id);
        if (!$time_dimension) {
            $this->flash->error("time_dimension was not found");

            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'index'
            ]);

            return;
        }

        if (!$time_dimension->delete()) {

            foreach ($time_dimension->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "time_dimension",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("time_dimension was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "time_dimension",
            'action' => "index"
        ]);
    }
}
