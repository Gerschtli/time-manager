<?php

namespace TimeManager\Service;

class TaskTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Task($this->app);
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
                    'end'   => '2015-10-11 12:34:45'
                ],
            ]
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
            ->method('getByName')
            ->with($this->equalTo('project'))
            ->will($this->returnValue(null));

        $this->app->modelProject
            ->expects($this->once())
            ->method('setName')
            ->with($this->equalTo('project'));

        $this->app->modelTime
            ->expects($this->at(0))
            ->method('setStart')
            ->with($this->equalTo(new \DateTime('2015-10-10 12:00:00')));
        $this->app->modelTime
            ->expects($this->at(1))
            ->method('setStart')
            ->with($this->equalTo(new \DateTime('2015-10-10 12:00:00')));
        $this->app->modelTime
            ->expects($this->at(2))
            ->method('setEnd')
            ->with($this->equalTo(new \DateTime('2015-10-11 12:34:45')));

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

    public function testCreateModelWithExistingProject()
    {
        $data = (object)[
            'description' => 'description',
            'project'     => 'project',
        ];

        $this->app->modelTask      = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->modelProject   = $this
            ->getMockBuilder('\TimeManager\Model\Project')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->serviceProject = $this
            ->getMockBuilder('\TimeManager\Service\Project')
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

        $this->app->serviceProject
            ->expects($this->once())
            ->method('getByName')
            ->with($this->equalTo('project'))
            ->will($this->returnValue($this->app->modelProject));

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

        $this->app->modelTime
            ->expects($this->at(0))
            ->method('setStart')
            ->with($this->equalTo(new \DateTime('2015-10-10 12:00:00')));
        $this->app->modelTime
            ->expects($this->at(1))
            ->method('setEnd')
            ->with($this->equalTo(new \DateTime('2015-10-11 12:34:45')));

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
}
