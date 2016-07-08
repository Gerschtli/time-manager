<?php

namespace TimeManager\Controller;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_infoPresenter;

    public function setUp()
    {
        parent::setUp();

        $this->_infoPresenter = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_object = new Error($this->_infoPresenter);
    }

    public function testErrorAction()
    {
        $this->_infoPresenter
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
        $this->_infoPresenter
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
