<?php

namespace TimeManager\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Model\Time as TimeModel;

/**
 * @SuppressWarnings(PMD.TooManyPublicMethods)
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_entityManager;
    private $_timeService;

    public function setUp()
    {
        parent::setUp();

        $this->_entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_timeService = $this
            ->getMockBuilder(Time::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Task(
            $this->_entityManager,
            $this->_timeService
        );
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
        $task              = new TaskModel();
        $task->description = 'bla';

        $taskWithId              = new TaskModel();
        $taskWithId->taskId      = 15;
        $taskWithId->description = 'bla';

        return [
            [
                (object) [],
                null,
            ],
            [
                (object) [
                    'description' => null,
                ],
                null,
            ],
            [
                (object) [
                    'description' => '',
                ],
                null,
            ],
            [
                (object) [
                    'dsa' => 'dsdsa',
                ],
                null,
            ],
            [
                (object) [
                    'description' => 'bla',
                ],
                $task,
            ],
            [
                (object) [
                    'description' => 'bla',
                    'times'       => null,
                ],
                $task,
            ],
            [
                (object) [
                    'description' => 'bla',
                    'times'       => '',
                ],
                $task,
            ],
            [
                (object) [
                    'description' => 'bla',
                    'times'       => [],
                ],
                $task,
            ],
            [
                (object) [
                    'taskId'      => 15,
                    'description' => 'bla',
                ],
                $taskWithId,
            ],
        ];
    }

    public function testConvertToEntityWithInvalidTime()
    {
        $data = (object) [
            'description' => 'bla',
            'times'       => [
                (object) [
                    'start' => null,
                ],
            ],
        ];

        $expected              = new TaskModel();
        $expected->description = 'bla';

        $this->_timeService
            ->expects($this->once())
            ->method('convertToEntity')
            ->with($this->equalTo((object) ['start' => null]))
            ->will($this->returnValue(null));

        $this->assertEquals(
            $expected,
            $this->_object->convertToEntity($data)
        );
    }

    public function testConvertToEntityWithValidTime()
    {
        $data = (object) [
            'description' => 'bla',
            'times'       => [
                (object) [
                    'start' => '2015-01-01 12:00:42',
                ],
            ],
        ];

        $timeModel        = new TimeModel();
        $timeModel->start = '2015-01-01 12:00:42';

        $expected              = new TaskModel();
        $expected->description = 'bla';
        $expected->times->add($timeModel);

        $this->_timeService
            ->expects($this->once())
            ->method('convertToEntity')
            ->with($this->equalTo((object) ['start' => '2015-01-01 12:00:42']))
            ->will($this->returnValue($timeModel));

        $this->assertEquals(
            $expected,
            $this->_object->convertToEntity($data)
        );
    }

    public function testDeleteById()
    {
        $taskId = time();

        $this->_entityManager
            ->expects($this->at(0))
            ->method('getReference')
            ->with(
                $this->equalTo(TaskModel::class),
                $this->equalTo($taskId)
            )
            ->will($this->returnValue('bla'));
        $this->_entityManager
            ->expects($this->at(1))
            ->method('remove')
            ->with($this->equalTo('bla'));
        $this->_entityManager
            ->expects($this->at(2))
            ->method('flush');

        $this->_object->deleteById($taskId);
    }

    public function testGetById()
    {
        $taskId = time();

        $this->_entityManager
            ->expects($this->once())
            ->method('find')
            ->with(
                $this->equalTo(TaskModel::class),
                $this->equalTo($taskId)
            )
            ->will($this->returnValue('bla'));

        $this->assertEquals('bla', $this->_object->getById($taskId));
    }

    public function testGetAll()
    {
        $repository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(TaskModel::class))
            ->will($this->returnValue($repository));

        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue('bla'));

        $this->assertEquals('bla', $this->_object->getAll());
    }

    public function testPersistEntity()
    {
        $entity              = new TaskModel();
        $entity->description = 'hdjsa';

        $this->_entityManager
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($entity));
        $this->_entityManager
            ->expects($this->at(1))
            ->method('flush');

        $this->_object->persistEntity($entity);
    }

    public function testUpdate()
    {
        $taskId = (time() % 20) + 1;

        $modelTask              = new TaskModel();
        $modelTask->taskId      = $taskId;
        $modelTask->description = 'bla';

        $modelTaskCopy              = new TaskModel();
        $modelTaskCopy->taskId      = $taskId;
        $modelTaskCopy->description = 'blax';

        $this->_entityManager
            ->expects($this->at(0))
            ->method('merge')
            ->with($this->equalTo($modelTask))
            ->will($this->returnValue($modelTaskCopy));
        $this->_entityManager
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals($modelTaskCopy, $this->_object->update($taskId, $modelTask));
    }

    public function testUpdateWhenEntityIsNew()
    {
        $taskId = time() % 20;

        $modelTask              = new TaskModel();
        $modelTask->taskId      = $taskId;
        $modelTask->description = 'bla';

        $this->_entityManager
            ->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($modelTask))
            ->will($this->throwException(new ORMInvalidArgumentException('exception')));
        $this->_entityManager
            ->expects($this->never())
            ->method('flush');

        $this->assertNull($this->_object->update($taskId, $modelTask));
    }

    /**
     * @dataProvider dataProviderForTestUpdateWhenIdIsInvalid
     */
    public function testUpdateWhenIdIsInvalid($taskId, $modelTask)
    {
        $this->_entityManager
            ->expects($this->never())
            ->method('merge');
        $this->_entityManager
            ->expects($this->never())
            ->method('flush');

        $this->assertNull($this->_object->update($taskId, $modelTask));
    }

    public function dataProviderForTestUpdateWhenIdIsInvalid()
    {
        $taskNoId              = new TaskModel();
        $taskNoId->description = 'bla';

        $taskWrongId              = new TaskModel();
        $taskWrongId->taskId      = 241;
        $taskWrongId->description = 'bla';

        return [
            [
                123,
                $taskNoId,
            ],
            [
                123,
                $taskWrongId,
            ],
        ];
    }
}
