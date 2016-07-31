<?php

namespace TimeManager\Controller;

use Exception;
use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use TimeManager\Presenter\Info;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_infoPresenter;
    private $_response;
    private $_logger;

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
        $this->_logger = $this
            ->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new Error(
            $this->_infoPresenter,
            $this->_response,
            $this->_logger
        );
    }

    public function testErrorAction()
    {
        $exception = new Exception('test');

        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_logger
            ->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('Exception occured'),
                $this->equalTo(['exception' => $exception])
            );

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($this->_response),
                $this->equalTo(500),
                $this->equalTo('test')
            )
            ->will($this->returnValue($this->_response));

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

        $this->_logger
            ->expects($this->once())
            ->method('info')
            ->with($this->equalTo('Not found'));

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
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

    public function testNotAllowedAction()
    {
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_logger
            ->expects($this->once())
            ->method('info')
            ->with(
                $this->equalTo('Method not allowed'),
                $this->equalTo(['methods' => ['POST', 'GET']])
            );

        $this->_infoPresenter
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo($this->_response),
                $this->equalTo(405),
                $this->equalTo('Available Methods: POST, GET')
            )
            ->will($this->returnValue($this->_response));

        $this->assertEquals(
            $this->_response,
            $this->_object->notAllowedAction($request, $response, ['POST', 'GET'])
        );
    }
}
