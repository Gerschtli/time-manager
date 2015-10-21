<?php

namespace TimeManager\Decorator;

class SuccessTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Success($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Decorator\Success', $this->_object);
        $this->assertInstanceOf('\TimeManager\Decorator\Base', $this->_object);
        $this->assertInstanceOf('\TimeManager\Decorator\Decorator', $this->_object);
    }

    public function testConstants()
    {
        $object = new \ReflectionClass('\TimeManager\Decorator\Success');
        $parent = new \ReflectionClass('\TimeManager\Decorator\Base');

        $this->assertEquals(
            [
                'STATUS_OK'      => 200,
                'STATUS_CREATED' => 201,
            ],
            $object->getConstants() + $parent->getConstants()
        );
    }

    /**
     * @dataProvider dataProviderForTestProcess
     */
    public function testProcess($code, $body)
    {
        $this->_object->process($code);

        $response = $this->app->response;
        $this->assertEquals($code, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }

    public function dataProviderForTestProcess()
    {
        return [
            [
                201,
                '{
                    "success": {
                        "code": 201,
                        "description": "Created"
                    }
                }',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestProcessWithMessage
     */
    public function testProcessWithMessage($code, $message, $body)
    {
        $this->_object->process($code, $message);

        $response = $this->app->response;
        $this->assertEquals($code, $response->getStatus());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJsonStringEqualsJsonString($body, $response->getBody());
    }

    public function dataProviderForTestProcessWithMessage()
    {
        return [
            [
                201,
                'bla blub',
                '{
                    "success": {
                        "code": 201,
                        "description": "Created, bla blub"
                    }
                }',
            ],
        ];
    }
}
