<?php

namespace TimeManager\Service;

/**
 * @SuppressWarnings(PMD.ExcessiveMethodLength)
 */
class TaskTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Task($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Service\Task', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testCreateModel()
    {
        $data = (object)[
            'description' => 'description',
            'project'     => 'project',
            'time'        => [
                (object)[
                    'start' => '2015-10-10 12:00:00',
                ],
                (object)[
                    'start' => '2015-10-10 12:00:00',
                    'end'   => '2015-10-11 12:34:45',
                ],
            ],
        ];

        $this->app->modelTask      = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->serviceProject = $this
            ->getMockBuilder('\TimeManager\Service\Project')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->modelProject   = $this
            ->getMockBuilder('\TimeManager\Model\Project')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->serviceTime    = $this
            ->getMockBuilder('\TimeManager\Service\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->modelTime      = $this
            ->getMockBuilder('\TimeManager\Model\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal           = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->modelTask
            ->expects($this->at(0))
            ->method('setDescription')
            ->with($this->equalTo('description'));
        $this->app->modelTask
            ->expects($this->at(1))
            ->method('setProject')
            ->with($this->equalTo($this->app->modelProject));
        $this->app->modelTask
            ->expects($this->at(2))
            ->method('addTime')
            ->with($this->equalTo($this->app->modelTime));
        $this->app->modelTask
            ->expects($this->at(3))
            ->method('addTime')
            ->with($this->equalTo($this->app->modelTime));

        $this->app->serviceProject
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo('project'))
            ->will($this->returnValue($this->app->modelProject));
        
        $this->app->serviceTime
            ->expects($this->at(0))
            ->method('createModel')
            ->with(
                $this->equalTo(
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                    ]
                )
            )
            ->will($this->returnValue($this->app->modelTime));
        $this->app->serviceTime
            ->expects($this->at(1))
            ->method('createModel')
            ->with(
                $this->equalTo(
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                        'end'   => '2015-10-11 12:34:45',
                    ]
                )
            )
            ->will($this->returnValue($this->app->modelTime));

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTask));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals(
            $this->app->modelTask,
            $this->_object->createModel($data)
        );
    }

    public function testCreateModelWithoutDescription()
    {
        $data = (object)[
            'project' => 'project',
        ];

        $this->assertNull($this->_object->createModel($data));
    }

    public function testCreateModelWithMinimumData()
    {
        $data = (object)[
            'description' => 'description',
        ];

        $this->app->modelTask = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal      = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->modelTask
            ->expects($this->at(0))
            ->method('setDescription')
            ->with($this->equalTo('description'));

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTask));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals(
            $this->app->modelTask,
            $this->_object->createModel($data)
        );
    }

    public function testCreateModelWithInvalidTime()
    {
        $data = (object)[
            'description' => 'description',
            'time'        => [
                (object)[
                    'start' => 'bla',
                ],
                (object)[
                    'start' => '2015-10-10 12:00:00',
                    'end'   => '2015-10-11 12:34:45'
                ],
            ]
        ];

        $this->app->modelTask      = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->serviceTime    = $this
            ->getMockBuilder('\TimeManager\Service\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->modelTime      = $this
            ->getMockBuilder('\TimeManager\Model\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal           = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->modelTask
            ->expects($this->at(0))
            ->method('setDescription')
            ->with($this->equalTo('description'));
        $this->app->modelTask
            ->expects($this->at(1))
            ->method('addTime')
            ->with($this->equalTo($this->app->modelTime));

        $this->app->serviceTime
            ->expects($this->at(0))
            ->method('createModel')
            ->with(
                $this->equalTo(
                    (object)[
                        'start' => 'bla',
                    ]
                )
            )
            ->will($this->returnValue($this->app->modelTime));
        $this->app->serviceTime
            ->expects($this->at(1))
            ->method('createModel')
            ->with(
                $this->equalTo(
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                        'end'   => '2015-10-11 12:34:45'
                    ]
                )
            )
            ->will($this->returnValue($this->app->modelTime));

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTask));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals(
            $this->app->modelTask,
            $this->_object->createModel($data)
        );
    }

    public function testGetById()
    {
        $taskId = time();

        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->dbal
            ->expects($this->once())
            ->method('find')
            ->with(
                $this->equalTo('\TimeManager\Model\Task'),
                $this->equalTo($taskId)
            )
            ->will($this->returnValue('bla'));

        $this->assertEquals('bla', $this->_object->getById($taskId));
    }
}
