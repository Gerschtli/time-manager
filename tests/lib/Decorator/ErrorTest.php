<?php

class ErrorTest extends \LocalWebTestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();
        $this->_object = new \TimeManager\Decorator\Error($this->app);
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
}
