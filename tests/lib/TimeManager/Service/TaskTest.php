<?php

namespace TimeManager\Service;

use Doctrine\Common\Collections\ArrayCollection;
use stdClass;

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
     * @SuppressWarnings(PMD.UnusedLocalVariable)
     */
    public function testCreateModel()
    {
        $data = (object)[
            'description' => 'description',
            'times'       => [
                (object)[
                    'start' => '2015-10-10 12:00:00',
                ],
                (object)[
                    'start' => '2015-10-10 12:00:00',
                    'end'   => '2015-10-11 12:34:45',
                ],
            ],
        ];

        $this->app->modelTask = (object)[
            'times' => new ArrayCollection(),
        ];

        $this->app->serviceTime = $this
            ->getMockBuilder('\TimeManager\Service\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal        = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

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
            ->will(
                $this->returnValue(
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                    ]
                )
            );
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
            ->will(
                $this->returnValue(
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                        'end'   => '2015-10-11 12:34:45',
                    ]
                )
            );

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTask));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $expected = (object)[
            'description' => 'description',
            'times' => new ArrayCollection(
                [
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                    ],
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                        'end'   => '2015-10-11 12:34:45',
                    ],
                ]
            ),
        ];

        $expected->times->forAll(
            function ($key, $value) use ($expected) {
                $value->task = $expected;
                return true;
            }
        );

        $this->assertEquals(
            $expected,
            $this->_object->createModel($data)
        );
    }

    public function testCreateModelWithoutDescription()
    {
        $data = new stdClass();

        $this->assertNull($this->_object->createModel($data));
    }

    public function testCreateModelWithMinimumData()
    {
        $data = (object)[
            'description' => 'description',
        ];

        $this->app->modelTask = new stdClass();

        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTask));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $this->assertEquals(
            (object)['description' => 'description'],
            $this->_object->createModel($data)
        );
    }

    /**
     * @SuppressWarnings(PMD.UnusedLocalVariable)
     */
    public function testCreateModelWithInvalidTime()
    {
        $data = (object)[
            'description' => 'description',
            'times'       => [
                (object)[
                    'start' => 'bla',
                ],
                (object)[
                    'start' => '2015-10-10 12:00:00',
                    'end'   => '2015-10-11 12:34:45',
                ],
            ],
        ];

        $this->app->modelTask = (object)[
            'times' => new ArrayCollection(),
        ];

        $this->app->serviceTime    = $this
            ->getMockBuilder('\TimeManager\Service\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->dbal           = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

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
            ->will($this->returnValue(null));
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
            ->will(
                $this->returnValue(
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                        'end'   => '2015-10-11 12:34:45',
                    ]
                )
            );

        $this->app->dbal
            ->expects($this->at(0))
            ->method('persist')
            ->with($this->equalTo($this->app->modelTask));
        $this->app->dbal
            ->expects($this->at(1))
            ->method('flush');

        $expected = (object)[
            'description' => 'description',
            'times'       => new ArrayCollection(
                [
                    (object)[
                        'start' => '2015-10-10 12:00:00',
                        'end'   => '2015-10-11 12:34:45',
                    ],
                ]
            ),
        ];

        $expected->times->forAll(
            function ($key, $value) use ($expected) {
                $value->task = $expected;
                return true;
            }
        );

        $this->assertEquals(
            $expected,
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

    public function testGetAll()
    {
        $this->app->dbal = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $repository      = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->dbal
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
}
