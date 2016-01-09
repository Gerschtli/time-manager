<?php

namespace TimeManager\Controller;

use TimeManager\AppAware;
use TimeManager\Decorator\Error;
use TimeManager\Decorator\Success;

class Task extends AppAware
{
    public function addAction()
    {
        $data = $this->_app->request->getBody();
        $task = $this->_app->serviceTask->createModel($data);

        if (!is_null($task)) {
            $this->_processSuccess(
                Success::STATUS_CREATED,
                ['taskId' => $task->taskId]
            );
        } else {
            $this->_processError(
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
            $this->_processError(
                Error::STATUS_NOT_FOUND
            );
        }
    }

    public function getAllAction()
    {
        $tasks = $this->_app->serviceTask->getAll();

        if (!empty($tasks)) {
            $this->_processData(
                Success::STATUS_OK,
                $tasks
            );
        } else {
            $this->_processError(
                Error::STATUS_NOT_FOUND
            );
        }
    }

    private function _processData($code, $data)
    {
        $this->_app->decoratorData->process($code, $data);
    }

    private function _processError($code, $message = '')
    {
        $this->_app->decoratorError->process($code, $message);
    }

    private function _processSuccess($code, $data)
    {
        $this->_app->decoratorSuccess->process($code, $data);
    }
}
