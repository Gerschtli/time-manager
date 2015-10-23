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
        $task    = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $project = $this
            ->getMockBuilder('\TimeManager\Model\Project')
            ->disableOriginalConstructor()
            ->getMock();
        $time    = $this
            ->getMockBuilder('\TimeManager\Model\Time')
            ->disableOriginalConstructor()
            ->getMock();

        $task->expects($this->at(0))
            ->method('getTaskId')
            ->will($this->returnValue(5));
        $task->expects($this->at(1))
            ->method('getDescription')
            ->will($this->returnValue('description'));
        $task->expects($this->at(2))
            ->method('getProject')
            ->will($this->returnValue($project));
        $task->expects($this->at(3))
            ->method('getTimes')
            ->will($this->returnValue([$time, $time]));

        $project->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('project'));

        $time->expects($this->at(0))
            ->method('getStart')
            ->with($this->equalTo(true))
            ->will($this->returnValue('2015-10-10'));
        $time->expects($this->at(1))
            ->method('getEnd')
            ->will($this->returnValue(null));
        $time->expects($this->at(2))
            ->method('getStart')
            ->with($this->equalTo(true))
            ->will($this->returnValue('2015-10-10'));
        $time->expects($this->at(3))
            ->method('getEnd')
            ->will($this->returnValue('2015-11-11'));
        $time->expects($this->at(4))
            ->method('getEnd')
            ->with($this->equalTo(true))
            ->will($this->returnValue('2015-11-11'));

        $body = json_encode(
            (object)[
                'taskId'      => 5,
                'description' => 'description',
                'project'     => 'project',
                'time'        => [
                    (object)[
                        'start' => '2015-10-10',
                    ],
                    (object)[
                        'start' => '2015-10-10',
                        'end'   => '2015-11-11',
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
