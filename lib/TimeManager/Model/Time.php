<?php

namespace TimeManager\Model;

use DateTime;

/**
 * @Entity
 * @Table(name="tm_time")
 */
class Time
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="timeId")
     * @var int
     */
    protected $_timeId;

    /**
     * @ManyToOne(targetEntity="Task", inversedBy="_times")
     * @JoinColumn(name="taskId", referencedColumnName="taskId")
     * @var \TimeManager\Model\Task
     */
    protected $_task;

    /**
     * @Column(type="datetime", name="start")
     * @var \DateTime
     */
    protected $_start;

    /**
     * @Column(type="datetime", name="end")
     * @var \DateTime
     */
    protected $_end;

    public function getTimeId()
    {
        return $this->_timeId;
    }

    public function setTask(Task $task)
    {
        $this->_task = $task;
    }

    public function getTask()
    {
        return $this->_task;
    }

    public function setStart(DateTime $start)
    {
        $this->_start = $start;
    }

    public function getStart($format = false)
    {
        if (!$format) {
            return $this->_start;
        }
        return $this->_formatDate($this->_start);
    }

    public function setEnd(DateTime $end)
    {
        $this->_end = $end;
    }

    public function getEnd($format = false)
    {
        if (!$format) {
            return $this->_end;
        }
        return $this->_formatDate($this->_end);
    }

    private function _formatDate(DateTime $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
}
