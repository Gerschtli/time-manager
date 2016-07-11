<?php

namespace TimeManager\Presenter;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use TimeManager\Model\Task;

class DataTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_headers;
    private $_response;

    public function setUp()
    {
        parent::setUp();

        $this->_headers = $this
            ->getMockBuilder('\Slim\Http\Headers')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_response = $this
            ->getMockBuilder('\Slim\Http\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_response->headers = $this->_headers;

        $this->_object = new Data($this->_response);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Presenter\Data', $this->_object);
        $this->assertInstanceOf('\TimeManager\Presenter\Presenter', $this->_object);
    }

    public function testProcess()
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

        $body = json_encode(
            (object) [
                'taskId'      => 5,
                'description' => 'description',
                'times'       => [
                    (object) [
                        'start' => '2015-10-10 00:00:00',
                    ],
                    (object) [
                        'start' => '2015-10-10 00:00:00',
                        'end'   => '2015-11-11 00:00:00',
                    ],
                ],
            ]
        );

        $this->_response
            ->expects($this->at(0))
            ->method('setStatus')
            ->with($this->equalTo(200));
        $this->_response
            ->expects($this->at(1))
            ->method('setBody')
            ->with(
                $this->callback(
                    function ($arg) use ($body) {
                        $this->assertJsonStringEqualsJsonString($body, $arg);
                        return true;
                    }
                )
            );

        $this->_headers
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('Content-Type'),
                $this->equalTo('application/json')
            );

        $this->_object->process(200, $task);
    }

    public function testProcessWithDataArray()
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

        $body = json_encode(
            [
                (object) [
                    'taskId'      => 5,
                    'description' => 'description',
                    'times'       => [
                        (object) [
                            'start' => '2015-10-10 00:00:00',
                        ],
                        (object) [
                            'start' => '2015-10-10 00:00:00',
                            'end'   => '2015-11-11 00:00:00',
                        ],
                    ],
                ],
            ]
        );

        $this->_response
            ->expects($this->at(0))
            ->method('setStatus')
            ->with($this->equalTo(200));
        $this->_response
            ->expects($this->at(1))
            ->method('setBody')
            ->with(
                $this->callback(
                    function ($arg) use ($body) {
                        $this->assertJsonStringEqualsJsonString($body, $arg);
                        return true;
                    }
                )
            );

        $this->_headers
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('Content-Type'),
                $this->equalTo('application/json')
            );

        $this->_object->process(200, [$task]);
    }
}
