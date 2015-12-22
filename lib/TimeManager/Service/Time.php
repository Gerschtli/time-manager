<?php

namespace TimeManager\Service;

use DateTime;
use TimeManager\AppAware;
use stdClass;

class Time extends AppAware
{
    public function createModel(stdClass $data)
    {
        if (empty($data->start) || !$this->_isValidDate($data->start)) {
            return null;
        }

        $time = $this->_app->modelTime;
        $time->setStart(new DateTime($data->start));

        if (!empty($data->end) && $this->_isValidDate($data->end)) {
            $time->setEnd(new DateTime($data->end));
        }

        $this->_app->dbal->persist($time);
        $this->_app->dbal->flush();

        return $time;
    }

    private function _isValidDate($date)
    {
        $parsed = date_parse($date);
        return empty($parsed['errors']);
    }
}
