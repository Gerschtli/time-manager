<?php

namespace TimeManager\Controller;

use stdclass;
use TimeManager\AppAware;
use TimeManager\Presenter\Data;
use TimeManager\Presenter\Error;

class Task extends AppAware
{
    public function addAction()
    {
        $data = $this->_app->request->getBody();
        $task = $this->_app->serviceTask->createModel($data);

        if (!is_null($task)) {
            $this->_processData(
                Data::STATUS_CREATED,
                $task
            );
        } else {
            $this->_processError(
                Error::STATUS_UNPROCESSABLE_ENTITY,
                Error::DESCRIPTION_INVALID_STRUCTURE
            );
        }
    }

    public function deleteAction($taskId)
    {
        $this->_app->serviceTask->deleteById($taskId);

        $this->_processData(
            Data::STATUS_OK,
            new stdclass()
        );
    }

    public function getAction($taskId)
    {
        $task = $this->_app->serviceTask->getById((int) $taskId);

        if (!is_null($task)) {
            $this->_processData(
                Data::STATUS_OK,
                $task
            );
        } else {
            $this->_processError(
                Error::STATUS_NOT_FOUND,
                Error::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    public function getAllAction()
    {
        $tasks = $this->_app->serviceTask->getAll();

        $this->_processData(
            Data::STATUS_OK,
            $tasks
        );
    }

    private function _processData($code, $data)
    {
        $this->_app->presenterData->process($code, $data);
    }

    private function _processError($code, $description)
    {
        $this->_app->presenterError->process($code, $description);
    }
}
