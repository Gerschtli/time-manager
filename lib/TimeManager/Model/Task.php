<?php

namespace TimeManager\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tm_tasks")
 */
class Task
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="taskId")
     * @var int
     */
    public $taskId;

    /**
     * @OneToOne(targetEntity="Project")
     * @JoinColumn(name="projectId", referencedColumnName="projectId")
     * @var \TimeManager\Model\Project
     */
    public $project;

    /**
     * @Column(type="string", name="description")
     * @var string
     */
    public $description;

    /**
     * @OneToMany(targetEntity="Time", mappedBy="task")
     * @var \TimeManager\Model\Time[]
     */
    public $times;

    public function __construct()
    {
        $this->times = new ArrayCollection();
    }
}
