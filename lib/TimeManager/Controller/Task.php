<?php

namespace TimeManager\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Presenter\Presenter;
use TimeManager\Service\Task as TaskService;
use TimeManager\Transformer\Task as TaskTransformer;

/**
 * @SuppressWarnings(PMD.UnusedFormalParameter)
 */
class Task
{
    private $_dataPresenter;
    private $_infoPresenter;
    private $_taskService;
    private $_transformer;

    public function __construct(
        DataPresenter $data, InfoPresenter $info, TaskService $task, TaskTransformer $transformer
    )
    {
        $this->_dataPresenter = $data;
        $this->_infoPresenter = $info;
        $this->_taskService   = $task;
        $this->_transformer   = $transformer;
    }

    public function addAction(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $task = $this->_transformer->transformToModel($data);

        if ($task !== null) {
            $this->_taskService->persistEntity($task);
            return $this->_dataPresenter->render(
                $response,
                Presenter::STATUS_CREATED,
                $task
            );
        } else {
            return $this->_infoPresenter->render(
                $response,
                Presenter::STATUS_UNPROCESSABLE_ENTITY,
                Presenter::DESCRIPTION_INVALID_STRUCTURE
            );
        }
    }

    public function deleteAction(Request $request, Response $response, array $args)
    {
        $this->_taskService->deleteById($args['taskId']);

        return $this->_infoPresenter->render(
            $response,
            Presenter::STATUS_OK,
            Presenter::DESCRIPTION_SUCCESSFUL_DELETION
        );
    }

    public function getAction(Request $request, Response $response, array $args)
    {
        $task = $this->_taskService->getById((int) $args['taskId']);

        if ($task !== null) {
            return $this->_dataPresenter->render(
                $response,
                Presenter::STATUS_OK,
                $task
            );
        } else {
            return $this->_infoPresenter->render(
                $response,
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }

    public function getAllAction(Request $request, Response $response, array $args)
    {
        $tasks = $this->_taskService->getAll();

        return $this->_dataPresenter->render(
            $response,
            Presenter::STATUS_OK,
            $tasks
        );
    }

    public function updateAction(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $task = $this->_transformer->transformToModel($data);

        if ($task === null) {
            return $this->_infoPresenter->render(
                $response,
                Presenter::STATUS_UNPROCESSABLE_ENTITY,
                Presenter::DESCRIPTION_INVALID_STRUCTURE
            );
        }

        $newTask = $this->_taskService->update($args['taskId'], $task);

        if ($newTask !== null) {
            return $this->_dataPresenter->render(
                $response,
                Presenter::STATUS_ACCEPTED,
                $newTask
            );
        } else {
            return $this->_infoPresenter->render(
                $response,
                Presenter::STATUS_NOT_FOUND,
                Presenter::DESCRIPTION_NONEXISTING_KEY
            );
        }
    }
}
