<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;
use TimeManager\Model\Task;
use TimeManager\Transformer\Task as TaskTransformer;

class DataTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_taskTransformer;

    public function setUp()
    {
        parent::setUp();

        $this->_taskTransformer = $this
            ->getMockBuilder(TaskTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Data(
            $this->_taskTransformer
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Data::class, $this->_object);
        $this->assertInstanceOf(Presenter::class, $this->_object);
    }

    public function testRender()
    {
        $data = ['test'];

        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->expects($this->once())
            ->method('withJson')
            ->with(
                $this->equalTo($data),
                $this->equalTo(200)
            )
            ->will($this->returnSelf());

        $this->assertEquals(
            $response,
            $this->_object->render($response, 200, $data)
        );
    }

    public function testRenderWithTask()
    {
        $task = new Task();
        $data = ['task'];

        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToApiObject')
            ->with($this->equalTo($task))
            ->will($this->returnValue($data));

        $response
            ->expects($this->once())
            ->method('withJson')
            ->with(
                $this->equalTo($data),
                $this->equalTo(200)
            )
            ->will($this->returnSelf());

        $this->assertEquals(
            $response,
            $this->_object->render($response, 200, $task)
        );
    }

    public function testRenderWithTaskList()
    {
        $task = new Task();
        $data = ['task'];

        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_taskTransformer
            ->expects($this->once())
            ->method('transformToApiObject')
            ->with($this->equalTo($task))
            ->will($this->returnValue($data));

        $response
            ->expects($this->once())
            ->method('withJson')
            ->with(
                $this->equalTo([$data]),
                $this->equalTo(200)
            )
            ->will($this->returnSelf());

        $this->assertEquals(
            $response,
            $this->_object->render($response, 200, [$task])
        );
    }
}
