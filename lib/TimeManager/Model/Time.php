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
    public $timeId;

    /**
     * @ManyToOne(targetEntity="Task", inversedBy="times")
     * @JoinColumn(name="taskId", referencedColumnName="taskId")
     * @var \TimeManager\Model\Task
     */
    public $task;

    /**
     * @Column(type="datetime", name="start")
     * @var \DateTime
     */
    public $start;

    /**
     * @Column(type="datetime", name="end")
     * @var \DateTime
     */
    public $end;
}
