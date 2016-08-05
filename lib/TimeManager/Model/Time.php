<?php

namespace TimeManager\Model;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="times")
 */
class Time
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="id")
     *
     * @var int
     */
    public $timeId;

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
