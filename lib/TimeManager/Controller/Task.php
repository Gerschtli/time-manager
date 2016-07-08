<?php

namespace TimeManager\Controller;

use TimeManager\Presenter\Base as Presenter;

class Task extends Controller
{
    public function addAction()
    {
        $data = $this->_app->request->getBody();
        $task = $this->_app->serviceTask->createModel($data);

        if (!is_null($task)) {
            $this->_getDataPresenter()->process(
                Presenter::STATUS_CREATED,
                $task
            );
        } else {
            $this->_getInfoPresenter()->process(
                Presenter::STATUS_UNPROCESSABLE_ENTITY,
                Presenter::DESCRIPTION_INVALID_STRUCTURE
            );
        }
    }

    public function deleteAction($taskId)
    {
        $this->_app->serviceTask->deleteById($taskId);

        $this->_getInfoPresenter()->process(
            Presenter::STATUS_OK,
            Presenter::DESCRIPTION_SUCCESSFUL_DELETION
        );
    }

    public function getAction($taskId)
    {
        $task = $this->_app->serviceTask->getById((int) $taskId);

        if (!is_null($task)) {
            $this->_getDataPresenter()->process(
                Presenter::STATUS_OK,
                $task
            );
        } else {
            $this->_getInfoPresenter()->process(
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    public function getAllAction()
    {
        $tasks = $this->_app->serviceTask->getAll();

        $this->_getDataPresenter()->process(
            Presenter::STATUS_OK,
            $tasks
        );
    }

    public function updateAction($taskId)
    {
        $data = $this->_app->request->getBody();
        $task = $this->_app->serviceTask->update($taskId, $data);

        if (!is_null($task)) {
            $this->_getDataPresenter()->process(
                Presenter::STATUS_ACCEPTED,
                $task
            );
        } else {
            $this->_getInfoPresenter()->process(
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    private function _getDataPresenter()
    {
        return $this->_app->presenterData;
    }
}
