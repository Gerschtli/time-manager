<?php

namespace TimeManager\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMInvalidArgumentException;
use TimeManager\Model\Task as TaskModel;

class Task
{
    private $_entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    public function deleteById($taskId)
    {
        $task = $this->_entityManager->getReference(TaskModel::class, $taskId);
        $this->_entityManager->remove($task);
        $this->_entityManager->flush();
    }

    public function getById($taskId)
    {
        return $this->_entityManager
            ->find(TaskModel::class, $taskId);
    }

    public function getAll()
    {
        return $this->_entityManager
            ->getRepository(TaskModel::class)
            ->findAll();
    }

    public function persistEntity(TaskModel $task)
    {
        $this->_entityManager->persist($task);
        $this->_entityManager->flush();
    }

    public function update($taskId, TaskModel $task)
    {
        if (empty($task->taskId) || $task->taskId != $taskId) {
            return null;
        }

        $copy = $this->_entityManager->merge($task);
        $this->_entityManager->flush();
        return $copy;
    }
}
