<?php

namespace TimeManager\Controller;

use Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use TimeManager\Presenter\Info;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_infoPresenter;

    public function setUp()
    {
        parent::setUp();

        $this->_infoPresenter = $this
            ->getMockBuilder(Info::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Error(
            $this->_infoPresenter,
            $this->_response
        );
    }

    public function testErrorAction()
    {
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo($this->_response),
                $this->equalTo(500),
                $this->equalTo('test')
            )
            ->will($this->returnValue($this->_response));

        $exception = new Exception('test');

        $this->assertEquals(
            $this->_response,
            $this->_object->errorAction($request, $response, $exception)
        );
    }

    public function testNotFoundAction()
    {
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo($this->_response),
                $this->equalTo(404),
                $this->equalTo('No existing Route matched')
            )
            ->will($this->returnValue($this->_response));

        $this->assertEquals(
            $this->_response,
            $this->_object->notFoundAction($request, $response)
        );
    }
}
