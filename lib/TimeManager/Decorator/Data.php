<?php

namespace TimeManager\Decorator;

use Closure;
use DateTime;
use TimeManager\Model\Task;
use TimeManager\Model\Time;

class Data extends Base implements Decorator
{
    public function process($code, $message = '')
    {
        if (is_array($message)) {
            foreach ($message as $key => $value) {
                $message[$key] = $this->_checkForParsing($value);
            }
        } else {
            $message = $this->_checkForParsing($message);
        }

        $this->_print($code, $message);
    }

    private function _checkForParsing($value)
    {
        if ($value instanceof Task) {
            $value = $this->_parseTask($value);
        }
        return $value;
    }

    private function _parseTask(Task $task)
    {
        $format = $this->_getFormatClosure();
        $clean  = $this->_getTimeCleanClosure($format);

        $task->project = $task->project->name;
        $task->times->forAll($clean);
        $task->times = $task->times->toArray();

        return $task;
    }

    private function _getFormatClosure()
    {
        return function(DateTime $date) {
            return $date->format('Y-m-d H:i:s');
        };
    }

    /**
     * @SuppressWarnings(PMD.UnusedLocalVariable)
     */
    private function _getTimeCleanClosure(Closure $format)
    {
        return function($key, $value) use ($format) {
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
