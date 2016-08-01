<?php

namespace TimeManager\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use stdClass;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Model\Time as TimeModel;
use TimeManager\Util\Date;

class Task
{
    private $_date;

    public function __construct(Date $date)
    {
        $this->_date = $date;
    }

    public function transformToModel(stdClass $data)
    {
        if (empty($data->description)) {
            return null;
        }

        $task              = new TaskModel();
        $task->description = $data->description;

        if (!empty($data->taskId)) {
            $task->taskId = $data->taskId;
        }

        if (!empty($data->times) && is_array($data->times)) {
            foreach ($data->times as $timeObject) {
                $this->_transformTime($task->times, $timeObject);
            }
        }

        return $task;
    }

    private function _transformTime(ArrayCollection $collection, stdClass $data)
    {
        $start = null;
        $end   = null;

        if (!empty($data->start)) {
            $start = $this->_date->convertToObject($data->start);
        }
        if (!empty($data->end)) {
            $end = $this->_date->convertToObject($data->end);
        }

        $time        = new TimeModel();
        $time->start = $start;
        $time->end   = $end;

        if ($time->start !== null) {
            $collection->add($time);
        }
    }
}
