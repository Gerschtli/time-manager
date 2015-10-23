<?php

namespace TimeManager\Model;

use DateTime;

/**
 * @Entity @Table(name="tm_time")
 */
class Time
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $timeId;

    /**
     * @ManyToOne(targetEntity="Task", inversedBy="times")
     * @JoinColumn(name="taskId", referencedColumnName="taskId")
     * @var \TimeManager\Model\Task
     */
    protected $task;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $start;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $end;

    public function getTimeId()
    {
        return $this->timeId;
    }

    public function setTask(Task $task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setStart(DateTime $start)
    {
        $this->start = $start;
    }

    public function getStart($format = false)
    {
        if (!$format) {
            return $this->start;
        }
        return $this->_formatDate($this->start);
    }

    public function setEnd(DateTime $end)
    {
        $this->end = $end;
    }

    public function getEnd($format = false)
    {
        if (!$format) {
            return $this->end;
        }
        return $this->_formatDate($this->end);
    }

    private function _formatDate(DateTime $date)
    {
        return $date->format('Y-m-d h:i:s');
    }
}
