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

    public function testAddAction()
    {
        $requestData = (object)[
            'description' => 'bla',
        ];

        \Slim\Environment::mock([
            'slim.input' => $requestData,
        ]);

        $this->app->serviceTask = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->serviceTask
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue((object)['bla' => 'test']));

        $this->app->decoratorSuccess = $this
            ->getMockBuilder('\TimeManager\Decorator\Success')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorSuccess
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(201));

        $this->_object->addAction();
    }

    public function testAddActionWithInvalidData()
    {
        $requestData = (object)[
            'test' => 'bla',
        ];

        \Slim\Environment::mock([
            'slim.input' => $requestData,
        ]);

        $this->app->serviceTask = $this
            ->getMockBuilder('\TimeManager\Service\Task')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->serviceTask
            ->expects($this->once())
            ->method('createModel')
            ->with($this->equalTo($requestData))
            ->will($this->returnValue(null));

        $this->app->decoratorError = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();
        $this->app->decoratorError
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(422), $this->equalTo('invalid data'));

        $this->_object->addAction();
    }
}
