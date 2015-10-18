<?php

namespace TimeManager\Model;

/**
 * @Entity @Table(name="tm_projects")
 */
class Project
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $projectId;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    public function getProjectId()
    {
        return $this->projectId;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
