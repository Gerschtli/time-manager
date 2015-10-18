<?php

namespace TimeManager\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="tm_tasks")
 */
class Task
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $taskId;

    /**
     * @OneToOne(targetEntity="Project")
     * @JoinColumn(name="projectId", referencedColumnName="projectId")
     * @var \TimeManager\Model\Project
     */
    protected $project;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $description;

    /**
     * @OneToMany(targetEntity="Time", mappedBy="task")
     * @var \TimeManager\Model\Time[]
     */
    protected $times;

    public function __construct()
    {
        $this->times = new ArrayCollection();
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setTimes(ArrayCollection $times)
    {
        $this->times = $times;
    }

    public function addTime(Time $time)
    {
        $time->setTask($this);
        $this->times[] = $time;
    }

    public function getTimes()
    {
        return $this->times;
    }
}
