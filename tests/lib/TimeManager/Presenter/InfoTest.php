<?php

namespace TimeManager\Presenter;

class InfoTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_headers;
    private $_response;

    public function setUp()
    {
        parent::setUp();

        $this->_headers = $this
            ->getMockBuilder('\Slim\Http\Headers')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_response = $this
            ->getMockBuilder('\Slim\Http\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_response->headers = $this->_headers;

        $this->_object = new Info($this->_response);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Presenter\Info', $this->_object);
        $this->assertInstanceOf('\TimeManager\Presenter\Presenter', $this->_object);
    }

    /**
     * @dataProvider dataProviderForTestProcess
     */
    public function testProcess($code, $description, $body)
    {
        $this->_response
            ->expects($this->at(0))
            ->method('setStatus')
            ->with($this->equalTo($code));
        $this->_response
            ->expects($this->at(1))
            ->method('setBody')
            ->with(
                $this->callback(
                    function ($arg) use ($body) {
                        $this->assertJsonStringEqualsJsonString($body, $arg);
                        return true;
                    }
                )
            );

        $this->_headers
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('Content-Type'),
                $this->equalTo('application/json')
            );

        $this->_object->process($code, $description);
    }

    public function dataProviderForTestProcess()
    {
        return [
            [
                415,
                'bla blub',
                '{
                    "code": 415,
                    "message": "Unsupported Media Type",
                    "description": "bla blub"
                }',
            ],
            [
                422,
                'xxx',
                '{
                    "code": 422,
                    "message": "Unprocessable Entity",
                    "description": "xxx"
                }',
            ],
            [
                500,
                'xxx',
                '{
                    "code": 500,
                    "message": "Internal Server Error",
                    "description": "xxx"
                }',
            ],
            [
                500,
                null,
                '{
                    "code": 500,
                    "message": "Internal Server Error"
                }',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestProcessWithReturn
     */
    public function testProcessWithReturn($code, $description, $body)
    {
        $return = $this->_object->process($code, $description, true);

        $this->assertJsonStringEqualsJsonString($body, $return);
    }

    public function dataProviderForTestProcessWithReturn()
    {
        return [
            [
                415,
                'bla blub',
                '{
                    "code": 415,
                    "message": "Unsupported Media Type",
                    "description": "bla blub"
                }',
            ],
            [
                422,
                'xxx',
                '{
                    "code": 422,
                    "message": "Unprocessable Entity",
                    "description": "xxx"
                }',
            ],
            [
                500,
                'xxx',
                '{
                    "code": 500,
                    "message": "Internal Server Error",
                    "description": "xxx"
                }',
            ],
            [
                500,
                null,
                '{
                    "code": 500,
                    "message": "Internal Server Error"
                }',
            ],
        ];
    }
}
