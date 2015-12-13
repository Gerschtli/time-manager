<?php

namespace TimeManager\Model;

/**
 * @Entity
 * @Table(name="tm_projects")
 */
class Project
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="projectId")
     * @var int
     */
    protected $_projectId;

    /**
     * @Column(type="string", name="name")
     * @var string
     */
    protected $_name;

    public function getProjectId()
    {
        return $this->_projectId;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }
}
