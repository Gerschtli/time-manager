<?php

namespace TimeManager\Service;

use stdClass;
use TimeManager\AppAware;

class Task extends AppAware
{
    private $_modelName = '\TimeManager\Model\Task';

    public function createModel(stdClass $data)
    {
        if (empty($data->description)) {
            return null;
        }

        $task              = $this->_app->modelTask;
        $task->description = $data->description;

        if (!empty($data->times) && is_array($data->times)) {
            foreach ($data->times as $timeObject) {
                $time = $this->_app->serviceTime->createModel($timeObject);
                if ($time != null) {
                    $time->task = $task;
                    $task->times->add($time);
                }
            }
        }

        $entityManager = $this->_getEntityManager();
        $entityManager->persist($task);
        $entityManager->flush();

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

    private function _getEntityManager()
    {
        return $this->_app->dbal;
    }
}
