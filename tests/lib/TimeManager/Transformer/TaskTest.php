<?php

namespace TimeManager\Transformer;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Model\Time as TimeModel;
use TimeManager\Util\Date;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_date;

    public function setUp()
    {
        parent::setUp();

        $this->_date = $this
            ->getMockBuilder(Date::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Task(
            $this->_date
        );
    }

    public function testTransformToApiObject()
    {
        $task              = new TaskModel();
        $task->taskId      = 5;
        $task->description = 'bla';

        $expected = (object) [
            'taskId'      => 5,
            'description' => 'bla',
            'times'       => [],
        ];

        $this->assertEquals(
            $expected,
            $this->_object->transformToApiObject($task)
        );
    }

    public function testTransformToApiObjectWithTime()
    {
        $this->_date
            ->expects($this->once())
            ->method('format')
            ->with($this->equalTo(new DateTime('2015-10-10 10:10:10')))
            ->will($this->returnValue('2015-10-10 10:10:10'));

        $time         = new TimeModel();
        $time->timeId = 10;
        $time->start  = new DateTime('2015-10-10 10:10:10');

        $task              = new TaskModel();
        $task->taskId      = 5;
        $task->description = 'bla';
        $task->times->add($time);

        $expected = (object) [
            'taskId'      => 5,
            'description' => 'bla',
            'times'       => [
                (object) [
                    'timeId' => 10,
                    'start'  => '2015-10-10 10:10:10',
                ],
            ],
        ];

        $this->assertEquals(
            $expected,
            $this->_object->transformToApiObject($task)
        );
    }

    public function testTransformToApiObjectWithTimeWithEnd()
    {
        $this->_date
            ->expects($this->at(0))
            ->method('format')
            ->with($this->equalTo(new DateTime('2015-10-10 10:10:10')))
            ->will($this->returnValue('2015-10-10 10:10:10'));
        $this->_date
            ->expects($this->at(1))
            ->method('format')
            ->with($this->equalTo(new DateTime('2015-10-11 10:10:10')))
            ->will($this->returnValue('2015-10-11 10:10:10'));

        $time         = new TimeModel();
        $time->timeId = 10;
        $time->start  = new DateTime('2015-10-10 10:10:10');
        $time->end    = new DateTime('2015-10-11 10:10:10');

        $task              = new TaskModel();
        $task->taskId      = 5;
        $task->description = 'bla';
        $task->times->add($time);

        $expected = (object) [
            'taskId'      => 5,
            'description' => 'bla',
            'times'       => [
                (object) [
                    'timeId' => 10,
                    'start'  => '2015-10-10 10:10:10',
                    'end'    => '2015-10-11 10:10:10',
                ],
            ],
        ];

        $this->assertEquals(
            $expected,
            $this->_object->transformToApiObject($task)
        );
    }

    /**
     * @dataProvider dataProviderForTestTransformToModel
     */
    public function testTransformToModel($data, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->_object->transformToModel($data)
        );
    }

    public function dataProviderForTestTransformToModel()
    {
        $taskOne              = new TaskModel();
        $taskOne->description = 'bla';

        $taskTwo              = new TaskModel();
        $taskTwo->description = 'bla';
        $taskTwo->taskId      = 10;

        return [
            [
                (object) [],
                null,
            ],
            [
                (object) [
                    'taskId' => 5,
                ],
                null,
            ],
            [
                (object) [
                    'description' => 'bla',
                ],
                $taskOne,
            ],
            [
                (object) [
                    'description' => 'bla',
                    'taskId'      => 10,
                ],
                $taskTwo,
            ],
            [
                (object) [
                    'description' => 'bla',
                    'taskId'      => 10,
                    'times'       => [],
                ],
                $taskTwo,
            ],
            [
                (object) [
                    'description' => 'bla',
                    'taskId'      => 10,
                    'times'       => [
                        (object) [
                            'bla' => 'test',
                        ],
                    ],
                ],
                $taskTwo,
            ],
        ];
    }

    public function testTransformToModelWithTime()
    {
        $data = (object) [
            'description' => 'bla',
            'times'       => [
                (object) [
                    'start' => '2015-10-10',
                    'end'   => '2015-10-11',
                ],
            ],
        ];

        $time        = new TimeModel();
        $time->start = new DateTime('2015-10-10');
        $time->end   = new DateTime('2015-10-11');

        $expected              = new TaskModel();
        $expected->description = 'bla';
        $expected->times       = new ArrayCollection();
        $expected->times->add($time);

        $this->_date
            ->expects($this->at(0))
            ->method('convertToObject')
            ->with($this->equalTo('2015-10-10'))
            ->will($this->returnValue(new DateTime('2015-10-10')));
        $this->_date
            ->expects($this->at(1))
            ->method('convertToObject')
            ->with($this->equalTo('2015-10-11'))
            ->will($this->returnValue(new DateTime('2015-10-11')));

        $this->assertEquals(
            $expected,
            $this->_object->transformToModel($data)
        );
    }

    public function testTransformToModelWithTimeWithOutEnd()
    {
        $data = (object) [
            'description' => 'bla',
            'times'       => [
                (object) [
                    'start' => '2015-10-10',
                ],
            ],
        ];

        $time        = new TimeModel();
        $time->start = new DateTime('2015-10-10');

        $expected              = new TaskModel();
        $expected->description = 'bla';
        $expected->times       = new ArrayCollection();
        $expected->times->add($time);

        $this->_date
            ->expects($this->once())
            ->method('convertToObject')
            ->with($this->equalTo('2015-10-10'))
            ->will($this->returnValue(new DateTime('2015-10-10')));

        $this->assertEquals(
            $expected,
            $this->_object->transformToModel($data)
        );
    }

    public function testTransformToModelWithTimeWithOutEndButId()
    {
        $data = (object) [
            'description' => 'bla',
            'times'       => [
                (object) [
                    'timeId' => 10,
                    'start'  => '2015-10-10',
                ],
            ],
        ];

        $time         = new TimeModel();
        $time->timeId = 10;
        $time->start  = new DateTime('2015-10-10');

        $expected              = new TaskModel();
        $expected->description = 'bla';
        $expected->times       = new ArrayCollection();
        $expected->times->add($time);

        $this->_date
            ->expects($this->once())
            ->method('convertToObject')
            ->with($this->equalTo('2015-10-10'))
            ->will($this->returnValue(new DateTime('2015-10-10')));

        $this->assertEquals(
            $expected,
            $this->_object->transformToModel($data)
        );
    }
}
