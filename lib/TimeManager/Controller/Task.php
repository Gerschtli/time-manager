<?php

namespace TimeManager\Controller;

use TimeManager\AppAware;
use TimeManager\Decorator\Error;
use TimeManager\Decorator\Success;

class Task extends AppAware
{
    public function addAction()
    {
        $data   = $this->_app->request->getBody();
        $result = $this->_app->serviceTask->createModel($data);

        if (!is_null($result)) {
            $this->_app->decoratorSuccess->process(
                Success::STATUS_CREATED,
                ['taskId' => $result->getTaskId()]
            );
        } else {
            $this->_app->decoratorError->process(
                Error::STATUS_UNPROCESSABLE_ENTITY,
                Error::MESSAGE_INVALID_DATA
            );
        }
    }

    public function getAction($taskId)
    {
        $task = $this->_app->serviceTask->getById((int) $taskId);

        if (!is_null($task)) {
            $this->_app->decoratorData->process(
                Success::STATUS_OK,
                $task
            );
        } else {
            $this->_app->decoratorError->process(
                Error::STATUS_NOT_FOUND
            );
        }
    }

    public function getAllAction()
    {
        $tasks = $this->_app->serviceTask->getAll();

        if (!empty($tasks)) {
            $this->_app->decoratorData->process(
                Success::STATUS_OK,
                $tasks
            );
        } else {
            $this->_app->decoratorError->process(
                Error::STATUS_NOT_FOUND
            );
        }
    }
}
