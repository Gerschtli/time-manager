<?php

namespace TimeManager\Service;

use DateTime;
use stdClass;
use TimeManager\AppAware;

class Time extends AppAware
{
    public function createModel(stdClass $data)
    {
        if (empty($data->start) || !$this->_isValidDate($data->start)) {
            return null;
        }

        $time        = $this->_app->modelTime;
        $time->start = new DateTime($data->start);

        if (!empty($data->end) && $this->_isValidDate($data->end)) {
            $time->end = new DateTime($data->end);
        }

        $entityManager = $this->_app->entityManager;
        $entityManager->persist($time);
        $entityManager->flush();

        return $time;
    }

    private function _isValidDate($date)
    {
        $parsed = date_parse($date);
        return empty($parsed['errors']);
    }
}
