<?php

namespace TimeManager\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tasks")
 */
class Task
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="id")
     *
     * @var int
     */
    public $taskId;

    /**
     * @Column(type="string", name="description")
     *
     * @var string
     */
    public $description;

    /**
     * @ManyToMany(targetEntity="Time", cascade={"persist"})
     * @JoinTable(name="tasks_times",
     *      joinColumns={@JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="time_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @var Time[]
     */
    public $times;

    public function __construct()
    {
        $this->times = new ArrayCollection();
    }
}
