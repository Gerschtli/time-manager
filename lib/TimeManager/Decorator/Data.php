<?php

namespace TimeManager\Decorator;

use stdClass;
use TimeManager\Model\Task;

class Data extends Base implements Decorator
{
    public function process($code, $message = '')
    {
        if ($message instanceof Task) {
            $message = $this->_parseTask($message);
        }
        $this->_print($code, $message);
    }

    private function _parseTask(Task $task)
    {
        $data = new stdClass();

        $data->description = $task->getDescription();
        $data->project     = $task->getProject()->getName();
        $data->time        = [];
        foreach ($task->getTimes() as $time) {
            $currTime        = new stdClass();
            $currTime->start = $time->getStart(true);

            if (!is_null($time->getEnd())) {
                $currTime->end = $time->getEnd(true);
            }
            $data->time[] = $currTime;
        }

        return $data;
    }
}
