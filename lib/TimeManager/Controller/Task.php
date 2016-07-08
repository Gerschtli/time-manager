<?php

namespace TimeManager\Controller;

use Slim\Http\Request;
use TimeManager\Presenter\Base as Presenter;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Service\Task as TaskService;

class Task
{
    private $_dataPresenter;
    private $_infoPresenter;
    private $_request;
    private $_taskService;

    public function __construct(
        DataPresenter $data, InfoPresenter $info, Request $request, TaskService $task
    )
    {
        $this->_dataPresenter = $data;
        $this->_infoPresenter = $info;
        $this->_request       = $request;
        $this->_taskService   = $task;
    }

    public function addAction()
    {
        $data = $this->_request->getBody();
        $task = $this->_taskService->convertToEntity($data);

        if (!is_null($task)) {
            $this->_taskService->persistEntity($task);
            $this->_dataPresenter->process(
                Presenter::STATUS_CREATED,
                $task
            );
        } else {
            $this->_infoPresenter->process(
                Presenter::STATUS_UNPROCESSABLE_ENTITY,
                Presenter::DESCRIPTION_INVALID_STRUCTURE
            );
        }
    }

    public function deleteAction($taskId)
    {
        $this->_taskService->deleteById($taskId);

        $this->_infoPresenter->process(
            Presenter::STATUS_OK,
            Presenter::DESCRIPTION_SUCCESSFUL_DELETION
        );
    }

    public function getAction($taskId)
    {
        $task = $this->_taskService->getById((int) $taskId);

        if (!is_null($task)) {
            $this->_dataPresenter->process(
                Presenter::STATUS_OK,
                $task
            );
        } else {
            $this->_infoPresenter->process(
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    public function getAllAction()
    {
        $tasks = $this->_taskService->getAll();

        $this->_dataPresenter->process(
            Presenter::STATUS_OK,
            $tasks
        );
    }

    public function updateAction($taskId)
    {
        $data = $this->_request->getBody();
        $task = $this->_taskService->update($taskId, $data);

        if (!is_null($task)) {
            $this->_dataPresenter->process(
                Presenter::STATUS_ACCEPTED,
                $task
            );
        } else {
            $this->_infoPresenter->process(
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }
}
