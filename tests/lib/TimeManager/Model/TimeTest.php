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

    /**
     * @dataProvider dataProviderForTestGetDateTime
     */
    public function testGetDateTime($property)
    {
        $datetime = $this
            ->getMockBuilder('\DateTime')
            ->disableOriginalConstructor()
            ->getMock();
        $datetime
            ->expects($this->once())
            ->method('format')
            ->with($this->equalTo('Y-m-d h:i:s'))
            ->will($this->returnValue('return'));

        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);

        $this->_object->$setter($datetime);
        $this->assertEquals('return', $this->_object->$getter(true));
    }

    public function dataProviderForTestGetDateTime()
    {
        return [
            ['start'],
            ['end'],
        ];
    }
}
