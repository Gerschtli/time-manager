<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;

class InfoTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    public function setUp()
    {
        parent::setUp();

        $this->_object = new Info();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Info::class, $this->_object);
        $this->assertInstanceOf(Presenter::class, $this->_object);
    }

    /**
     * @dataProvider dataProviderForTestRender
     */
    public function testRender($code, $description, $body)
    {
        $response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->expects($this->at(0))
            ->method('withStatus')
            ->with($this->equalTo($code))
            ->will($this->returnSelf());
        $response
            ->expects($this->at(1))
            ->method('getReasonPhrase')
            ->will($this->returnValue('message'));
        $response
            ->expects($this->at(2))
            ->method('withJson')
            ->with(
                $this->equalTo($body),
                $this->equalTo($code)
            )
            ->will($this->returnSelf());

        $this->assertEquals(
            $response,
            $this->_object->render($response, $code, $description)
        );
    }

    public function dataProviderForTestRender()
    {
        return [
            [
                415,
                'bla blub',
                (object) [
                    'code'        => 415,
                    'message'     => 'message',
                    'description' => 'bla blub',
                ],
            ],
            [
                500,
                null,
                (object) [
                    'code'        => 500,
                    'message'     => 'message',
                ],
            ],
        ];
    }
}
