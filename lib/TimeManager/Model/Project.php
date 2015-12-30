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
    public $projectId;

    /**
     * @Column(type="string", name="name")
     * @var string
     */
    public $name;
}
