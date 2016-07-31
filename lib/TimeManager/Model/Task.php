<?php

namespace TimeManager\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

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
     * @OneToMany(targetEntity="Time", mappedBy="task")
     *
     * @var Time[]
     */
    public $times;

    public function __construct()
    {
        $this->times = new ArrayCollection();
    }
}
