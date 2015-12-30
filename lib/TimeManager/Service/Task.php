<?php

namespace TimeManager\Service;

use stdClass;
use TimeManager\AppAware;

class Task extends AppAware
{
    public function createModel(stdClass $data)
    {
        if (empty($data->description)) {
            return null;
        }

        $dbal = $this->_app->dbal;

        $task = $this->_app->modelTask;
        $task->description = $data->description;

        if (!empty($data->project)) {
            $project = $this->_app->serviceProject->createModel($data->project);
            $task->project = $project;
        }

        if (!empty($data->times) && is_array($data->times)) {
            foreach ($data->times as $timeObject) {
                $time = $this->_app->serviceTime->createModel($timeObject);
                if ($time != null) {
                    $time->task = $task;
                    $task->times->add($time);
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

    public function getAll()
    {
        return $this->_app->dbal
            ->getRepository('\TimeManager\Model\Task')
            ->findAll();
    }
}
