<?php

namespace TimeManager\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\ORMInvalidArgumentException;
use stdClass;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Model\Time as TimeModel;

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

    /**
     * @dataProvider dataProviderForTestConvertToEntity
     */
    public function testConvertToEntity($data, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->_object->convertToEntity($data)
        );
    }

    public function dataProviderForTestConvertToEntity()
    {
        $task = new TaskModel();
        $task->description = 'bla';

        return [
            [
                (object)[],
                null,
            ],
            [
                (object)[
                    'description' => null,
                ],
                null,
            ],
            [
                (object)[
                    'description' => '',
                ],
                null,
            ],
            [
                (object)[
                    'dsa' => 'dsdsa',
                ],
                null,
            ],
            [
                (object)[
                    'description' => 'bla',
                ],
                $task,
            ],
            [
                (object)[
                    'description' => 'bla',
                    'times'       => null,
                ],
                $task,
            ],
            [
                (object)[
                    'description' => 'bla',
                    'times'       => '',
                ],
                $task,
            ],
            [
                (object)[
                    'description' => 'bla',
                    'times'       => [],
                ],
                $task,
            ],
        ];
    }

    public function testConvertToEntityWithInvalidTime()
    {
        $data = (object)[
            'description' => 'bla',
            'times'       => [
                (object)[
                    'start' => null,
                ],
            ],
        ];

        $expected = new TaskModel();
        $expected->description = 'bla';

        $this->app->serviceTime = $this
            ->getMockBuilder('\TimeManager\Service\Time')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTime
            ->expects($this->once())
            ->method('convertToEntity')
            ->with($this->equalTo((object)['start' => null]))
            ->will($this->returnValue(null));

        $this->assertEquals(
            $expected,
            $this->_object->convertToEntity($data)
        );
    }

    public function testConvertToEntityWithValidTime()
    {
        $data = (object)[
            'description' => 'bla',
            'times'       => [
                (object)[
                    'start' => '2015-01-01 12:00:42',
                ],
            ],
        ];

        $timeModel = new TimeModel();
        $timeModel->start = '2015-01-01 12:00:42';

        $expected = new TaskModel();
        $expected->description = 'bla';
        $expected->times->add($timeModel);

        $this->app->serviceTime = $this
            ->getMockBuilder('\TimeManager\Service\Time')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTime
            ->expects($this->once())
            ->method('convertToEntity')
            ->with($this->equalTo((object)['start' => '2015-01-01 12:00:42']))
            ->will($this->returnValue($timeModel));

        $this->assertEquals(
            $expected,
            $this->_object->convertToEntity($data)
        );
    }

    public function testDeleteById()
    {
        $taskId = time();

        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->at(0))
            ->method('getReference')
            ->with(
                $this->equalTo('\TimeManager\Model\Task'),
                $this->equalTo($taskId)
            )
            ->will($this->returnValue('bla'));
        $this->app->entityManager
            ->expects($this->at(1))
            ->method('remove')
            ->with($this->equalTo('bla'));
        $this->app->entityManager
            ->expects($this->at(2))
            ->method('flush');

        $this->_object->deleteById($taskId);
    }

    public function testGetById()
    {
        $taskId = time();

        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(
                $this->equalTo('\TimeManager\Model\Task'),
                $this->equalTo($taskId)
            )
            ->will($this->returnValue('bla'));

        $this->assertEquals('bla', $this->_object->getById($taskId));
    }

    public function testGetAll()
    {
        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $repository               = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('\TimeManager\Model\Task'))
            ->will($this->returnValue($repository));

        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue('bla'));

        $this->assertEquals('bla', $this->_object->getAll());
    }

    public function testPersistEntity()
    {
        $entity = new TaskModel();
        $entity->description = 'hdjsa';

        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($entity));
        $this->app->entityManager
            ->expects($this->at(1))
            ->method('flush');

        $this->_object->persistEntity($entity);
    }

    public function testUpdate()
    {
        $taskId = time() % 20;

        $modelTask = (object)[
            'taskId'      => $taskId,
            'description' => 'bla',
        ];
        $modelTaskCopy = (object)[
            'taskId'      => $taskId,
            'description' => 'blax',
        ];

        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->at(0))
            ->method('merge')
            ->with($this->equalTo($modelTask))
            ->will($this->returnValue($modelTaskCopy));
        $this->app->entityManager
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals($modelTaskCopy, $this->_object->update($taskId, $modelTask));
    }

    public function testUpdateWhenEntityIsNew()
    {
        $taskId = time() % 20;

        $modelTask = (object)[
            'taskId'      => $taskId,
            'description' => 'bla',
        ];

        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($modelTask))
            ->will($this->throwException(new ORMInvalidArgumentException('exception')));
        $this->app->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->assertNull($this->_object->update($taskId, $modelTask));
    }

    /**
     * @dataProvider dataProviderForTestUpdateWhenIdIsInvalid
     */
    public function testUpdateWhenIdIsInvalid($taskId, $modelTask)
    {
        $taskId = time() % 20;

        $modelTask = (object)[
            'taskId'      => $taskId,
            'description' => 'bla',
        ];

        $this->app->entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->entityManager
            ->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($modelTask))
            ->will($this->throwException(new ORMInvalidArgumentException('exception')));
        $this->app->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->assertNull($this->_object->update($taskId, $modelTask));
    }

    public function dataProviderForTestUpdateWhenIdIsInvalid()
    {
        return [
            [
                123,
                (object)[
                    'description' => 'bla',
                ],
            ],
            [
                123,
                (object)[
                    'taskId'      => null,
                    'description' => 'bla',
                ],
            ],
            [
                123,
                (object)[
                    'taskId'      => 241,
                    'description' => 'bla',
                ],
            ],
        ];
    }
}
