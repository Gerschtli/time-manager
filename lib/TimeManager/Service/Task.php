<?php

namespace TimeManager\Service;

use Slim\Slim;
use stdClass;

class Task
{
    private $_app;

    public function __construct(Slim $app)
    {
        $this->_app = $app;
    }

    public function createModel(stdClass $data)
    {
        if (empty($data->description)) {
            return null;
        }

        $dbal = $this->_app->dbal;

        $task = $this->_app->modelTask;
        $task->setDescription($data->description);

        if (!empty($data->project)) {
            $project = $this->_app->serviceProject->createModel($data->project);
            $task->setProject($project);
        }

        if (!empty($data->time) && is_array($data->time)) {
            foreach ($data->time as $timeObject) {
                $time = $this->_app->serviceTime->createModel($timeObject);
                if ($time != null) {
                    $task->addTime($time);
                }
            }
        }

        $dbal->persist($task);
        $dbal->flush();

        return $task;
    }

    public function getById($taskId)
    {
        return $this->_app->dbal
            ->find('\TimeManager\Model\Task', $taskId);
    }
}
