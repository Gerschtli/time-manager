<?php

namespace TimeManager\Controller;

use Slim\Environment;
use stdclass;

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

        Environment::mock(
            [
                'slim.input' => $requestData,
            ]
        );

        $modelTask = (object)['taskId' => time()];

        $this->app->serviceTask   = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->presenterData = $this
            ->getMockBuilder('\TimeManager\Presenter\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($modelTask));

        $this->app->presenterData
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(201),
                $this->equalTo($modelTask)
            );

        $this->_object->addAction();
    }

    public function testAddActionWithInvalidData()
    {
        $requestData = (object)[
            'test' => 'bla',
        ];

        Environment::mock(
            [
                'slim.input' => $requestData,
            ]
        );

        $this->app->serviceTask    = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->presenterError = $this
            ->getMockBuilder('\TimeManager\Presenter\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->app->presenterError
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(422),
                $this->equalTo('JSON is in invalid data structure')
            );

        $this->_object->addAction();
    }

    public function testDeleteAction()
    {
        $taskId = time();

        $this->app->serviceTask   = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->presenterData = $this
            ->getMockBuilder('\TimeManager\Presenter\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('deleteById')
            ->with($this->equalTo($taskId));

        $this->app->presenterData
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo(new stdclass())
            );

        $this->_object->deleteAction($taskId);
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
        $this->app->presenterData = $this
            ->getMockBuilder('\TimeManager\Presenter\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue($this->app->modelTask));

        $this->app->presenterData
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
        $this->app->presenterError = $this
            ->getMockBuilder('\TimeManager\Presenter\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue(null));

        $this->app->presenterError
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(404),
                $this->equalTo('No Data with provided Key found')
            );

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
        $this->app->presenterData = $this
            ->getMockBuilder('\TimeManager\Presenter\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue([$modelTaskOne, $modelTaskTwo]));

        $this->app->presenterData
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo([$modelTaskOne, $modelTaskTwo])
            );

        $this->_object->getAllAction();
    }
}
