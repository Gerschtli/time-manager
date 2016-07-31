<?php

namespace TimeManager\Model;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

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
     *
     * @var int
     */
    public $timeId;

    /**
     * @ManyToOne(targetEntity="Task", inversedBy="times")
     * @JoinColumn(name="taskId", referencedColumnName="taskId")
     *
     * @var Task
     */
    public $task;

    /**
     * @Column(type="datetime", name="start")
     *
     * @var DateTime
     */
    public $start;

    /**
     * @Column(type="datetime", name="end")
     *
     * @var DateTime
     */
    public $end;
}
