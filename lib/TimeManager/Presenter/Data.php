<?php

namespace TimeManager\Presenter;

use Closure;
use DateTime;
use TimeManager\Model\Task;

class Data extends Base
{
    const STATUS_OK      = 200;
    const STATUS_CREATED = 201;

    public function process($code, $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->_checkForParsing($value);
            }
        } else {
            $data = $this->_checkForParsing($data);
        }

        $this->_print($code, $data);
    }

    private function _checkForParsing($data)
    {
        if ($data instanceof Task) {
            $data = $this->_parseTask($data);
        }
        return $data;
    }

    private function _parseTask(Task $task)
    {
        $format = $this->_getFormatClosure();
        $clean  = $this->_getTimeCleanClosure($format);

        $task->times->forAll($clean);
        $task->times = $task->times->toArray();

        return $task;
    }

    private function _getFormatClosure()
    {
        return function (DateTime $date) {
            return $date->format('Y-m-d H:i:s');
        };
    }

    /**
     * @SuppressWarnings(PMD.UnusedLocalVariable)
     */
    private function _getTimeCleanClosure(Closure $format)
    {
        return function ($key, $value) use ($format) {
            unset($value->timeId, $value->task);

            $value->start = $format($value->start);
            if (empty($value->end)) {
                unset($value->end);
            } else {
                $value->end = $format($value->end);
            }
            return true;
        };
    }
}