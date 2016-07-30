<?php

namespace TimeManager\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Presenter\Presenter;
use TimeManager\Service\Task as TaskService;

class Task
{
    private $_dataPresenter;
    private $_infoPresenter;
    private $_taskService;

    public function __construct(
        DataPresenter $data, InfoPresenter $info, TaskService $task
    )
    {
        $this->_dataPresenter = $data;
        $this->_infoPresenter = $info;
        $this->_taskService   = $task;
    }

    public function addAction(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $task = $this->_taskService->convertToEntity($data);

        if (!is_null($task)) {
            $this->_taskService->persistEntity($task);
            return $this->_dataPresenter->process(
                $response,
                Presenter::STATUS_CREATED,
                $task
            );
        } else {
            return $this->_infoPresenter->process(
                $response,
                Presenter::STATUS_UNPROCESSABLE_ENTITY,
                Presenter::DESCRIPTION_INVALID_STRUCTURE
            );
        }
    }

    public function deleteAction(Request $request, Response $response, array $args)
    {
        $this->_taskService->deleteById($args['taskId']);

        return $this->_infoPresenter->process(
            $response,
            Presenter::STATUS_OK,
            Presenter::DESCRIPTION_SUCCESSFUL_DELETION
        );
    }

    public function getAction(Request $request, Response $response, array $args)
    {
        $task = $this->_taskService->getById((int) $args['taskId']);

        if (!is_null($task)) {
            return $this->_dataPresenter->process(
                $response,
                Presenter::STATUS_OK,
                $task
            );
        } else {
            return $this->_infoPresenter->process(
                $response,
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    public function getAllAction(Request $request, Response $response, array $args)
    {
        $tasks = $this->_taskService->getAll();

        return $this->_dataPresenter->process(
            $response,
            Presenter::STATUS_OK,
            $tasks
        );
    }

    public function updateAction(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $task = $this->_taskService->update($args['taskId'], $data);

        if (!is_null($task)) {
            return $this->_dataPresenter->process(
                $response,
                Presenter::STATUS_ACCEPTED,
                $task
            );
        } else {
            return $this->_infoPresenter->process(
                $response,
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }
}
