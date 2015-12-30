<?php

namespace TimeManager\Controller;

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
        $this->assertInstanceOf('\TimeManager\Controller\Task', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testAddAction()
    {
        $requestData = (object)[
            'description' => 'bla',
        ];

        \Slim\Environment::mock(
            [
                'slim.input' => $requestData,
            ]
        );

        $this->app->serviceTask      = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->modelTask        = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorSuccess = $this
            ->getMockBuilder('\TimeManager\Decorator\Success')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($this->app->modelTask));

        $this->app->modelTask
            ->expects($this->once())
            ->method('getTaskId')
            ->will($this->returnValue(5));

        $this->app->decoratorSuccess
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(201),
                $this->equalTo(['taskId' => 5])
            );

        $this->_object->addAction();
    }

    public function testAddActionWithInvalidData()
    {
        $requestData = (object)[
            'test' => 'bla',
        ];

        \Slim\Environment::mock(
            [
                'slim.input' => $requestData,
            ]
        );

        $this->app->serviceTask    = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorError = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->app->decoratorError
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(422), $this->equalTo('invalid data'));

        $this->_object->addAction();
    }

    public function testGetAction()
    {
        $taskId = time();

        $this->app->serviceTask   = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->modelTask     = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorData = $this
            ->getMockBuilder('\TimeManager\Decorator\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue($this->app->modelTask));

        $this->app->decoratorData
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo($this->app->modelTask)
            );

        $this->_object->getAction($taskId);
    }

    public function testGetActionWithoutValidId()
    {
        $taskId = time();

        $this->app->serviceTask    = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorError = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue(null));

        $this->app->decoratorError
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(404));

        $this->_object->getAction($taskId);
    }

    public function testGetAllAction()
    {
        $this->app->serviceTask   = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $modelTaskOne             = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $modelTaskTwo             = $this
            ->getMockBuilder('\TimeManager\Model\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorData = $this
            ->getMockBuilder('\TimeManager\Decorator\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue([$modelTaskOne, $modelTaskTwo]));

        $this->app->decoratorData
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo([$modelTaskOne, $modelTaskTwo])
            );

        $this->_object->getAllAction();
    }

    public function testGetAllActionWithNoTasks()
    {
        $this->app->serviceTask   = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorError = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue([]));

         $this->app->decoratorError
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(404));

        $this->_object->getAllAction();
    }
}
