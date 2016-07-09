<?php

namespace TimeManager\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMInvalidArgumentException;
use stdClass;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Service\Time as TimeService;

class Task
{
    private $_modelName = '\TimeManager\Model\Task';

    private $_entityManager;
    private $_timeService;

    public function __construct(EntityManager $entityManager, TimeService $timeService)
    {
        $this->_entityManager = $entityManager;
        $this->_timeService   = $timeService;
    }

    public function convertToEntity(stdClass $data)
    {
        if (empty($data->description)) {
            return null;
        }

        $task              = new TaskModel();
        $task->description = $data->description;

        if (!empty($data->times) && is_array($data->times)) {
            foreach ($data->times as $timeObject) {
                $time = $this->_timeService->convertToEntity($timeObject);
                if ($time !== null) {
                    $time->task = $task;
                    $task->times->add($time);
                }
            }
        }

        return $task;
    }

    public function deleteById($taskId)
    {
        $task = $this->_entityManager->getReference($this->_modelName, $taskId);
        $this->_entityManager->remove($task);
        $this->_entityManager->flush();
    }

    public function getById($taskId)
    {
        return $this->_entityManager
            ->find($this->_modelName, $taskId);
    }

    public function getAll()
    {
        return $this->_entityManager
            ->getRepository($this->_modelName)
            ->findAll();
    }

    public function persistEntity(TaskModel $task)
    {
        $this->_entityManager->persist($task);
        $this->_entityManager->flush();
    }

    public function update($taskId, stdClass $data)
    {
        if (empty($data->taskId) || $data->taskId != $taskId) {
            return null;
        }
        try {
            $copy = $this->_entityManager->merge($data);
            $this->_entityManager->flush();
            return $copy;
        } catch (ORMInvalidArgumentException $exception) {
            return null;
        }
    }
}
