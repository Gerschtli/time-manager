<?php

namespace TimeManager\Model;

class TimeTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Time();
    }

    public function testGetTimeId()
    {
        $timeId             = time();
        $reflectionClass    = new \ReflectionClass('\TimeManager\Model\Time');
        $reflectionProperty = $reflectionClass->getProperty('timeId');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->_object, $timeId);

        $this->assertEquals($timeId, $this->_object->getTimeId());
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
        $task     = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $datetime = $this
            ->getMockBuilder('\DateTime')
            ->disableOriginalConstructor()
            ->getMock();

        return [
            ['task', $task],
            ['start', $datetime],
            ['end', $datetime],
        ];
    }
}
