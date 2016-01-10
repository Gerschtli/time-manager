<?php

namespace TimeManager\Decorator;

use ReflectionClass;

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
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
    }

    public function testConstants()
    {
        $object = new ReflectionClass('\TimeManager\Decorator\Error');
        $parent = new ReflectionClass('\TimeManager\Decorator\Base');

        $this->assertEquals(
            [
                'STATUS_BAD_REQUEST'            => 400,
                'STATUS_NOT_FOUND'              => 404,
                'STATUS_UNSUPPORTED_MEDIA_TYPE' => 415,
                'STATUS_UNPROCESSABLE_ENTITY'   => 422,
                'DESCRIPTION_INVALID_STRUCTURE' => 'JSON is in invalid data structure',
                'DESCRIPTION_NONEXISTING_KEY'   => 'No Data with provided Key found',
                'DESCRIPTION_ONLY_JSON'         => 'Only JSON is allowed',
                'DESCRIPTION_PARSE_ERROR'       => 'JSON Parse Error',
            ],
            $object->getConstants() + $parent->getConstants()
        );
    }

    /**
     * @dataProvider dataProviderForTestProcess
     */
    public function testProcess($code, $description, $body)
    {
        $this->_object->process($code, $description);

        $response = $this->app->response;
        $this->assertEquals($code, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
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
        ];
    }
}
