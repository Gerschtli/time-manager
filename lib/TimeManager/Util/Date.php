<?php

namespace TimeManager\Util;

use DateTime;

class Date
{
    public function convertToObject($date)
    {
        if (!$this->_isValidDate($date)) {
            return null;
        }
        return new DateTime($date);
    }

    public function format(DateTime $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    private function _isValidDate($date)
    {
        $parsed = date_parse($date);
        return empty($parsed['errors']);
    }
}
