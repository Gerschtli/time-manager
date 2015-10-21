<?php

namespace TimeManager\Decorator;

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
        $this->assertInstanceOf('\TimeManager\Decorator\Error', $this->_object);
        $this->assertInstanceOf('\TimeManager\Decorator\Base', $this->_object);
        $this->assertInstanceOf('\TimeManager\Decorator\Decorator', $this->_object);
    }

    public function testConstants()
    {
        $object = new \ReflectionClass('\TimeManager\Decorator\Error');
        $parent = new \ReflectionClass('\TimeManager\Decorator\Base');

        $this->assertEquals(
            [
                'STATUS_NOT_FOUND'               => 404,
                'STATUS_UNSUPPORTED_MEDIA_TYPE'  => 415,
                'STATUS_UNPROCESSABLE_ENTITY'    => 422,
                'MESSAGE_UNSUPPORTED_MEDIA_TYPE' => 'only JSON is allowed',
                'MESSAGE_UNPROCESSABLE_ENTITY'   => 'invalid JSON',
                'MESSAGE_INVALID_DATA'           => 'invalid data',
            ],
            $object->getConstants() + $parent->getConstants()
        );
    }

    /**
     * @dataProvider dataProviderForTestProcess
     */
    public function testProcess($errorCode, $body)
    {
        $this->_object->process($errorCode);

        $response = $this->app->response;
        $this->assertEquals($errorCode, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }

    public function dataProviderForTestProcess()
    {
        return [
            [
                415,
                '{
                    "error": {
                        "code": 415,
                        "description": "Unsupported Media Type, only JSON is allowed"
                    }
                }',
            ],
            [
                422,
                '{
                    "error": {
                        "code": 422,
                        "description": "Unprocessable Entity, invalid JSON"
                    }
                }',
            ],
            [
                500,
                '{
                    "error": {
                        "code": 500,
                        "description": "Internal Server Error"
                    }
                }',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestProcessWithMessage
     */
    public function testProcessWithMessage($errorCode, $message, $body)
    {
        $this->_object->process($errorCode, $message);

        $response = $this->app->response;
        $this->assertEquals($errorCode, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }

    public function dataProviderForTestProcessWithMessage()
    {
        return [
            [
                415,
                'bla blub',
                '{
                    "error": {
                        "code": 415,
                        "description": "Unsupported Media Type, bla blub"
                    }
                }',
            ],
            [
                422,
                'xxx',
                '{
                    "error": {
                        "code": 422,
                        "description": "Unprocessable Entity, xxx"
                    }
                }',
            ],
            [
                500,
                'xxx',
                '{
                    "error": {
                        "code": 500,
                        "description": "Internal Server Error, xxx"
                    }
                }',
            ],
        ];
    }
}
