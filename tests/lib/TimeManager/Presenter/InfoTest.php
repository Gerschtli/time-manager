<?php

namespace TimeManager\Presenter;

class InfoTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new Info($this->app);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Presenter\Info', $this->_object);
        $this->assertInstanceOf('\TimeManager\Presenter\Presenter', $this->_object);
        $this->assertInstanceOf('\TimeManager\AppAware', $this->_object);
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
