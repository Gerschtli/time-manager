<?php

namespace TimeManager\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use stdClass;
use TimeManager\Model\Time as TimeModel;

class Time
{
    private $_entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    public function convertToEntity(stdClass $data)
    {
        if (empty($data->start) || !$this->_isValidDate($data->start)) {
            return null;
        }

        $time        = new TimeModel();
        $time->start = new DateTime($data->start);

        if (!empty($data->end) && $this->_isValidDate($data->end)) {
            $time->end = new DateTime($data->end);
        }

        return $time;
    }

    public function persistEntity(TimeModel $time)
    {
        $this->_entityManager->persist($time);
        $this->_entityManager->flush();
    }

    private function _isValidDate($date)
    {
        $parsed = date_parse($date);
        return empty($parsed['errors']);
    }
}
