<?php

namespace TimeManager\Service;

use DateTime;
use stdClass;
use TimeManager\AppAware;
use TimeManager\Model\Time as TimeModel;

class Time extends AppAware
{
    public function persistEntity(TimeModel $time)
    {
        $this->_app->entityManager->persist($time);
        $this->_app->entityManager->flush();
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

    private function _isValidDate($date)
    {
        $parsed = date_parse($date);
        return empty($parsed['errors']);
    }
}
