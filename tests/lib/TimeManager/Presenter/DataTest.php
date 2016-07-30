<?php

namespace TimeManager\Presenter;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Slim\Http\Response;
use TimeManager\Model\Task;

class DataTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();

        $this->_object = new Data();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Data::class, $this->_object);
        $this->assertInstanceOf(Presenter::class, $this->_object);
    }

    public function testRender()
    {
        $task              = new Task();
        $task->taskId      = 5;
        $task->description = 'description';
        $task->times       = new ArrayCollection(
            [
                (object) [
                    'start' => new DateTime('2015-10-10'),
                ],
                (object) [
                    'start' => new DateTime('2015-10-10'),
                    'end'   => new DateTime('2015-11-11'),
                ],
            ]
        );

        $expected              = new Task();
        $expected->taskId      = 5;
        $expected->description = 'description';
        $expected->times       = [
            (object) [
                'start' => '2015-10-10 00:00:00',
            ],
            (object) [
                'start' => '2015-10-10 00:00:00',
                'end'   => '2015-11-11 00:00:00',
            ],
        ];

        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->expects($this->once())
            ->method('withJson')
            ->with(
                $this->equalTo($expected),
                $this->equalTo(200)
            )
            ->will($this->returnSelf());

        $this->assertEquals(
            $response,
            $this->_object->render($response, 200, $task)
        );
    }

    public function testRenderWithDataArray()
    {
        $task              = new Task();
        $task->taskId      = 5;
        $task->description = 'description';
        $task->times       = new ArrayCollection(
            [
                (object) [
                    'start' => new DateTime('2015-10-10'),
                ],
                (object) [
                    'start' => new DateTime('2015-10-10'),
                    'end'   => new DateTime('2015-11-11'),
                ],
            ]
        );

        $expected              = new Task();
        $expected->taskId      = 5;
        $expected->description = 'description';
        $expected->times       = [
            (object) [
                'start' => '2015-10-10 00:00:00',
            ],
            (object) [
                'start' => '2015-10-10 00:00:00',
                'end'   => '2015-11-11 00:00:00',
            ],
        ];

        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->expects($this->once())
            ->method('withJson')
            ->with(
                $this->equalTo([$expected]),
                $this->equalTo(200)
            )
            ->will($this->returnSelf());

        $this->assertEquals(
            $response,
            $this->_object->render($response, 200, [$task])
        );
    }
}
