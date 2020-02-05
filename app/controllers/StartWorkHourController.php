<?php
declare(strict_types=1);

 

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class StartWorkHourController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for start_work_hour
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, 'StartWorkHour', $_GET)->getParams();
        $parameters['order'] = "id";

        $start_work_hour = StartWorkHour::find($parameters);
        if (count($start_work_hour) == 0) {
            $this->flash->notice("The search did not find any start_work_hour");

            $this->dispatcher->forward([
                "controller" => "start_work_hour",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $start_work_hour,
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
     * Edits a start_work_hour
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $start_work_hour = StartWorkHour::findFirstByid($id);
            if (!$start_work_hour) {
                $this->flash->error("start_work_hour was not found");

                $this->dispatcher->forward([
                    'controller' => "start_work_hour",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $start_work_hour->getId();

            $this->tag->setDefault("id", $start_work_hour->getId());
            $this->tag->setDefault("time", $start_work_hour->getTime());
            
        }
    }

    /**
     * Creates a new start_work_hour
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'index'
            ]);

            return;
        }

        $start_work_hour = new StartWorkHour();
        $start_work_hour->settime($this->request->getPost("time", "int"));
        

        if (!$start_work_hour->save()) {
            foreach ($start_work_hour->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("start_work_hour was created successfully");

        $this->dispatcher->forward([
            'controller' => "start_work_hour",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a start_work_hour edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $start_work_hour = StartWorkHour::findFirstByid($id);

        if (!$start_work_hour) {
            $this->flash->error("start_work_hour does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'index'
            ]);

            return;
        }

        $start_work_hour->settime($this->request->getPost("time", "int"));
        

        if (!$start_work_hour->save()) {

            foreach ($start_work_hour->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'edit',
                'params' => [$start_work_hour->getId()]
            ]);

            return;
        }

        $this->flash->success("start_work_hour was updated successfully");

        $this->dispatcher->forward([
            'controller' => "start_work_hour",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a start_work_hour
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $start_work_hour = StartWorkHour::findFirstByid($id);
        if (!$start_work_hour) {
            $this->flash->error("start_work_hour was not found");

            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'index'
            ]);

            return;
        }

        if (!$start_work_hour->delete()) {

            foreach ($start_work_hour->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "start_work_hour",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("start_work_hour was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "start_work_hour",
            'action' => "index"
        ]);
    }
}
