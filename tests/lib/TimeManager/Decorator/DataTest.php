<?php

namespace TimeManager\Decorator;

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
        $this->assertInstanceOf('\TimeManager\Decorator\Data', $this->_object);
        $this->assertInstanceOf('\TimeManager\Decorator\Base', $this->_object);
        $this->assertInstanceOf('\TimeManager\Decorator\Decorator', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testProcess()
    {
        $this->_object->process(200);

        $response = $this->app->response;
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('""', $response->getBody());
    }

    public function testProcessWithMessage()
    {
        $task = new \TimeManager\Model\Task();
        $task->taskId      = 5;
        $task->description = 'description';
        $task->project     = (object)[
            'name' => 'project',
        ];
        $task->times       = new \Doctrine\Common\Collections\ArrayCollection(
            [
                (object)[
                    'start' => new \DateTime('2015-10-10'),
                ],
                (object)[
                    'start' => new \DateTime('2015-10-10'),
                    'end'   => new \DateTime('2015-11-11'),
                ],
            ]
        );

        $body = json_encode(
            (object)[
                'taskId'      => 5,
                'description' => 'description',
                'project'     => 'project',
                'times'       => [
                    (object)[
                        'start' => '2015-10-10 00:00:00',
                    ],
                    (object)[
                        'start' => '2015-10-10 00:00:00',
                        'end'   => '2015-11-11 00:00:00',
                    ],
                ]
            ]
        );

        $this->_object->process(200, $task);

        $response = $this->app->response;
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }
}
