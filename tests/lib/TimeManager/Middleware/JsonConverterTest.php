<?php

namespace TimeManager\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class JsonConverterTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_request;
    private $_response;

    private $_isCalled;

    public function setUp()
    {
        parent::setUp();

        $this->_request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new JsonConverter();
    }

    public function testInvoke()
    {
        $this->_isCalled = false;

        $next = [$this, 'nextCallable'];

        $this->_request
            ->expects($this->once())
            ->method('registerMediaTypeParser')
            ->with(
                $this->equalTo('application/json'),
                $this->callback(
                    function ($callback) {
                        $this->assertTrue(is_callable($callback));
                        $this->assertEquals(
                            (object) ['test' => 'bla'],
                            $callback('{"test": "bla"}')
                        );
                        return true;
                    }
                )
            );

        $this->assertEquals(
            'return',
            call_user_func_array($this->_object, [$this->_request, $this->_response, $next])
        );
        $this->assertTrue($this->_isCalled);
    }

    public function nextCallable(Request $request, Response $response)
    {
        $this->_isCalled = true;

        $this->assertEquals($this->_request, $request);
        $this->assertEquals($this->_response, $response);

        return 'return';
    }
}
