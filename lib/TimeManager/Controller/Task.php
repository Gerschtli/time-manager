<?php

namespace TimeManager\Controller;

use stdclass;
use TimeManager\AppAware;
use TimeManager\Presenter\Base as Presenter;

class Task extends AppAware
{
    public function addAction()
    {
        $data = $this->_app->request->getBody();
        $task = $this->_app->serviceTask->createModel($data);

        if (!is_null($task)) {
            $this->_processData(
                Presenter::STATUS_CREATED,
                $task
            );
        } else {
            $this->_processInfo(
                Presenter::STATUS_UNPROCESSABLE_ENTITY,
                Presenter::DESCRIPTION_INVALID_STRUCTURE
            );
        }
    }

    public function deleteAction($taskId)
    {
        $this->_app->serviceTask->deleteById($taskId);

        $this->_processInfo(
            Presenter::STATUS_OK,
            Presenter::DESCRIPTION_SUCCESSFUL_DELETION
        );
    }

    public function getAction($taskId)
    {
        $task = $this->_app->serviceTask->getById((int) $taskId);

        if (!is_null($task)) {
            $this->_processData(
                Presenter::STATUS_OK,
                $task
            );
        } else {
            $this->_processInfo(
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    public function getAllAction()
    {
        $tasks = $this->_app->serviceTask->getAll();

        $this->_processData(
            Presenter::STATUS_OK,
            $tasks
        );
    }

    private function _processData($code, $data)
    {
        $this->_app->presenterData->process($code, $data);
    }

    private function _processInfo($code, $description)
    {
        $this->_app->presenterInfo->process($code, $description);
    }
}
