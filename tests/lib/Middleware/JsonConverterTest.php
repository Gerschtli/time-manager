<?php

class JsonConverterTest extends \LocalWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        ob_start();
    }

    public function tearDown()
    {
        ob_end_clean();
        parent::tearDown();
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCall($method)
    {
        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $method,
            'CONTENT_TYPE'   => 'application/json',
            'slim.input'     => '{"foo":"bar"}'
        ));
        $slim = new \Slim\Slim();
        $slim->add(new \TimeManager\Middleware\JsonConverter());
        $slim->run();

        $this->assertTrue(is_object($slim->request->getBody()));
        $this->assertEquals((object)['foo' => 'bar'], $slim->request->getBody());
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCallInvalidJson($method)
    {
        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $method,
            'CONTENT_TYPE'   => 'application/json',
            'slim.input'     => '{"foo":"bar"'
        ));
        
        $slim = new \Slim\Slim();
        $slim->add(new \TimeManager\Middleware\JsonConverter());
        $slim->errorDecorator = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();
        $slim->errorDecorator
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(422));
        
        $slim->run();
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCallUnsupportedMediaType($method)
    {
        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $method,
            'CONTENT_TYPE'   => 'application/xml',
            'slim.input'     => 'irgendwas'
        ));

        $slim = new \Slim\Slim();
        $slim->add(new \TimeManager\Middleware\JsonConverter());
        $slim->errorDecorator = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();
        $slim->errorDecorator
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo(415));

        $slim->run();
    }

    public function dataProviderForTestCall()
    {
        return [
            ['POST'],
            ['PUT'],
            ['DELETE'],
        ];
    }
}
