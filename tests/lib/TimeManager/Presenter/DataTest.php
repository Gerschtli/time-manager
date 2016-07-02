<?php

namespace TimeManager\Presenter;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use TimeManager\Model\Task;

class DataTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Data($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Presenter\Data', $this->_object);
        $this->assertInstanceOf('\TimeManager\Presenter\Base', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testProcess()
    {
        $task = new Task();
        $task->taskId      = 5;
        $task->description = 'description';
        $task->times       = new ArrayCollection(
            [
                (object)[
                    'start' => new DateTime('2015-10-10'),
                ],
                (object)[
                    'start' => new DateTime('2015-10-10'),
                    'end'   => new DateTime('2015-11-11'),
                ],
            ]
        );

        $body = json_encode(
            (object)[
                'taskId'      => 5,
                'description' => 'description',
                'times'       => [
                    (object)[
                        'start' => '2015-10-10 00:00:00',
                    ],
                    (object)[
                        'start' => '2015-10-10 00:00:00',
                        'end'   => '2015-11-11 00:00:00',
                    ],
                ],
            ]
        );

        $this->_object->process(200, $task);

        $response = $this->app->response;
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }

    public function testProcessWithDataArray()
    {
        $task = new Task();
        $task->taskId      = 5;
        $task->description = 'description';
        $task->times       = new ArrayCollection(
            [
                (object)[
                    'start' => new DateTime('2015-10-10'),
                ],
                (object)[
                    'start' => new DateTime('2015-10-10'),
                    'end'   => new DateTime('2015-11-11'),
                ],
            ]
        );

        $body = json_encode(
            [
                (object)[
                    'taskId'      => 5,
                    'description' => 'description',
                    'times'       => [
                        (object)[
                            'start' => '2015-10-10 00:00:00',
                        ],
                        (object)[
                            'start' => '2015-10-10 00:00:00',
                            'end'   => '2015-11-11 00:00:00',
                        ],
                    ],
                ],
            ]
        );

        $this->_object->process(200, [$task]);

        $response = $this->app->response;
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }
}