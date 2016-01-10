<?php

namespace TimeManager\Middleware;

use Slim\Environment;
use Slim\Slim;

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
        Environment::mock(
            [
                'REQUEST_METHOD' => $method,
                'CONTENT_TYPE'   => 'application/json',
                'slim.input'     => '{"foo":"bar"}',
            ]
        );
        $slim = new Slim();
        $slim->add(new JsonConverter());
        $slim->run();

        $this->assertTrue(is_object($slim->request->getBody()));
        $this->assertEquals((object)['foo' => 'bar'], $slim->request->getBody());
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCallInvalidJson($method)
    {
        Environment::mock(
            [
                'REQUEST_METHOD' => $method,
                'CONTENT_TYPE'   => 'application/json',
                'slim.input'     => '{"foo":"bar"',
            ]
        );

        $slim = new Slim();
        $slim->add(new JsonConverter());
        $slim->errorDecorator = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();
        $slim->errorDecorator
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(400),
                $this->equalTo('JSON Parse Error')
            );

        $slim->run();
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCallUnsupportedMediaType($method)
    {
        Environment::mock(
            [
                'REQUEST_METHOD' => $method,
                'CONTENT_TYPE'   => 'application/xml',
                'slim.input'     => 'irgendwas',
            ]
        );

        $slim = new Slim();
        $slim->add(new JsonConverter());
        $slim->errorDecorator = $this
            ->getMockBuilder('\TimeManager\Decorator\Error')
            ->disableOriginalConstructor()
            ->getMock();
        $slim->errorDecorator
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(415),
                $this->equalTo('Only JSON is allowed')
            );

        $slim->run();
    }

    public function dataProviderForTestCall()
    {
        return [
            ['POST'],
            ['PUT'],
        ];
    }

    /**
     * @dataProvider dataProviderForTestCallNoCheck
     */
    public function testCallNoCheck($method)
    {
        Environment::mock(
            [
                'REQUEST_METHOD' => $method,
                'CONTENT_TYPE'   => 'application/xml',
                'slim.input'     => 'irgendwas',
            ]
        );

        $slim = new Slim();
        $slim->add(new JsonConverter());
        $slim->run();

        $this->assertEquals('irgendwas', $slim->request->getBody());
    }

    public function dataProviderForTestCallNoCheck()
    {
        return [
            ['GET'],
            ['DELETE'],
        ];
    }
}
