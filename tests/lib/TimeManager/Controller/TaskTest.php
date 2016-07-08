<?php

namespace TimeManager\Controller;

use Slim\Environment;
use TimeManager\Model\Task as TaskModel;

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
        $this->assertInstanceOf('\TimeManager\Controller\Controller', $this->_object);
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

        $modelTask = new TaskModel();
        $modelTask->description = 'bla';

        $this->app->serviceTask   = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->presenterData = $this
            ->getMockBuilder('\TimeManager\Presenter\Data')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->at(0))
            ->method('convertToEntity')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue($modelTask));
        $this->app->serviceTask
            ->expects($this->at(1))
            ->method('persistEntity')
            ->with($this->equalTo($modelTask));

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
        $this->app->presenterInfo = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('convertToEntity')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));
        $this->app->serviceTask
            ->expects($this->never())
            ->method('persistEntity');

        $this->app->presenterInfo
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
        $this->app->presenterInfo = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('deleteById')
            ->with($this->equalTo($taskId));

        $this->app->presenterInfo
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(200),
                $this->equalTo('Deletion successful')
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
        $this->app->presenterInfo = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($taskId))
            ->will($this->returnValue(null));

        $this->app->presenterInfo
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

    public function testUpdateAction()
    {
        $taskId = time();
        $requestData = (object)[
            'description' => 'bla',
        ];

        Environment::mock(
            [
                'slim.input' => $requestData,
            ]
        );

        $modelTask = (object)['taskId' => $taskId];

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
            ->method('update')
            ->with($this->equalTo($taskId), $this->equalTo($requestData))
            ->will($this->returnValue($modelTask));

        $this->app->presenterData
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(202),
                $this->equalTo($modelTask)
            );

        $this->_object->updateAction($taskId);
    }

    public function testUpdateActionWithInvalidData()
    {
        $taskId = time();
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
        $this->app->presenterInfo = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->serviceTask
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($taskId), $this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->app->presenterInfo
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(404),
                $this->equalTo('No Data with provided Key found')
            );

        $this->_object->updateAction($taskId);
    }
}
