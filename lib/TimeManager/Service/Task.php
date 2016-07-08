<?php

namespace TimeManager\Service;

use Doctrine\ORM\ORMInvalidArgumentException;
use stdClass;
use TimeManager\AppAware;
use TimeManager\Model\Task as TaskModel;

class Task extends AppAware
{
    private $_modelName = '\TimeManager\Model\Task';

    public function convertToEntity(stdClass $data)
    {
        if (empty($data->description)) {
            return null;
        }

        $task = new TaskModel();
        $task->description = $data->description;

        if (!empty($data->times) && is_array($data->times)) {
            foreach ($data->times as $timeObject) {
                $time = $this->_app->serviceTime->convertToEntity($timeObject);
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
        $entityManager = $this->_getEntityManager();

        $task = $entityManager->getReference($this->_modelName, $taskId);
        $entityManager->remove($task);
        $entityManager->flush();
    }

    public function getById($taskId)
    {
        return $this->_getEntityManager()
            ->find($this->_modelName, $taskId);
    }

    public function getAll()
    {
        return $this->_getEntityManager()
            ->getRepository($this->_modelName)
            ->findAll();
    }

    public function persistEntity(TaskModel $task)
    {
        $this->_getEntityManager()->persist($task);
        $this->_getEntityManager()->flush();
    }

    public function update($taskId, stdClass $data)
    {
        if (empty($data->taskId) || $data->taskId != $taskId) {
            return null;
        }
        try {
            $copy = $this->_getEntityManager()->merge($data);
            $this->_getEntityManager()->flush();
            return $copy;
        } catch (ORMInvalidArgumentException $exception) {
            return null;
        }
    }

    private function _getEntityManager()
    {
        return $this->_app->entityManager;
    }
}
