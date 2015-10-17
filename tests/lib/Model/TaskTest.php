<?php

namespace TimeManager\Model;

class TaskTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Task();
    }

    public function testGetTaskId()
    {
        $taskId             = time();
        $reflectionClass    = new \ReflectionClass('\TimeManager\Model\Task');
        $reflectionProperty = $reflectionClass->getProperty('taskId');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->_object, $taskId);

        $this->assertEquals($taskId, $this->_object->getTaskId());
    }

    /**
     * @dataProvider dataProviderForTestGetterSetter
     */
    public function testGetterSetter($name, $value)
    {
        $setter = 'set' . ucfirst($name);
        $getter = 'get' . ucfirst($name);

        $this->_object->$setter($value);
        $this->assertEquals($value, $this->_object->$getter());
    }

    public function dataProviderForTestGetterSetter()
    {
        $project         = $this->getMock('\TimeManager\Model\Project');
        $arrayCollection = $this->getMock('\Doctrine\Common\Collections\ArrayCollection');

        return [
            ['project', $project],
            ['description', 'description'],
            ['times', $arrayCollection],
        ];
    }

    public function testAddTime()
    {
        $time = $this->getMock('\TimeManager\Model\Time');

        $this->_object->addTime($time);
        $times = $this->_object->getTimes();

        $this->assertEquals(1, count($times));
        $this->assertEquals($time, $times[0]);
    }
}
