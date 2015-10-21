<?php

namespace TimeManager\Service;

use DateTime;
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
            $serviceProject = $this->_app->serviceProject;
            $project        = $serviceProject->getByName($data->project);
            if (is_null($project)) {
                $project = $this->_app->modelProject;
                $project->setName($data->project);
            }

            $dbal->persist($project);
            $task->setProject($project);
        }

        if (!empty($data->time) && is_array($data->time)) {
            foreach ($data->time as $timeObject) {
                if (empty($timeObject->start) || !$this->_isValidDate($timeObject->start)) {
                    continue;
                }

                $time = $this->_app->modelTime;
                $time->setStart(new DateTime($timeObject->start));

                if (!empty($timeObject->end) && $this->_isValidDate($timeObject->end)) {
                    $time->setEnd(new DateTime($timeObject->end));
                }

                $task->addTime($time);
                $dbal->persist($time);
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

    private function _isValidDate($date)
    {
        $parsed = date_parse($date);
        return empty($parsed['errors']);
    }
}
