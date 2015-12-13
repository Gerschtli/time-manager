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
    protected $_taskId;

    /**
     * @OneToOne(targetEntity="Project")
     * @JoinColumn(name="projectId", referencedColumnName="projectId")
     * @var \TimeManager\Model\Project
     */
    protected $_project;

    /**
     * @Column(type="string", name="description")
     * @var string
     */
    protected $_description;

    /**
     * @OneToMany(targetEntity="Time", mappedBy="_task")
     * @var \TimeManager\Model\Time[]
     */
    protected $_times;

    public function __construct()
    {
        $this->_times = new ArrayCollection();
    }

    public function getTaskId()
    {
        return $this->_taskId;
    }

    public function setProject(Project $project)
    {
        $this->_project = $project;
    }

    public function getProject()
    {
        return $this->_project;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setTimes(ArrayCollection $times)
    {
        $this->_times = $times;
    }

    public function addTime(Time $time)
    {
        $time->setTask($this);
        $this->_times[] = $time;
    }

    public function getTimes()
    {
        return $this->_times;
    }
}
