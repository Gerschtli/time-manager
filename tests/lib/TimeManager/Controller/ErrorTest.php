<?php

namespace TimeManager\Controller;

class ErrorTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Error($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Controller\Error', $this->_object);
        $this->assertInstanceOf('\TimeManager\Controller\Controller', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testErrorAction()
    {
        $this->app->presenterInfo = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->presenterInfo
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(500),
                $this->equalTo(null),
                $this->equalTo(true)
            )
            ->will($this->returnValue('return'));

        ob_start();
        $this->_object->errorAction();
        $this->assertEquals('return', ob_get_clean());
    }

    public function testNotFoundAction()
    {
        $this->app->presenterInfo = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->presenterInfo
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(404),
                $this->equalTo('No existing Route matched'),
                $this->equalTo(true)
            )
            ->will($this->returnValue('return'));

        ob_start();
        $this->_object->notFoundAction();
        $this->assertEquals('return', ob_get_clean());
    }
}
