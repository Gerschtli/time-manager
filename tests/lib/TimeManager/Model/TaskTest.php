<?php

namespace TimeManager\Model;

use Doctrine\Common\Collections\ArrayCollection;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Task();
    }

    public function testConstuctor()
    {
        $this->assertInstanceOf(
            ArrayCollection::class,
            $this->_object->times
        );
        $this->assertEquals(0, count($this->_object->times));
    }
}
