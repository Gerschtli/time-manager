<?php

namespace TimeManager\Middleware;

class JsonConverterTest extends \PHPUnit_Framework_TestCase
{
    private $_object;
    private $_environment;
    private $_infoPresenter;
    private $_next;
    private $_request;

    public function setUp()
    {
        parent::setUp();

        $this->_environment = $this
            ->getMockBuilder('\Slim\Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_infoPresenter = $this
            ->getMockBuilder('\TimeManager\Presenter\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_next = $this
            ->getMockBuilder('\Slim\Middleware')
            ->disableOriginalConstructor()
            ->setMethods(['call'])
            ->getMockForAbstractClass();
        $this->_request = $this
            ->getMockBuilder('\Slim\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new JsonConverter(
            $this->_environment, $this->_infoPresenter, $this->_request
        );
        $this->_object->setNextMiddleware($this->_next);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('\TimeManager\Middleware\JsonConverter', $this->_object);
        $this->assertInstanceOf('\Slim\Middleware', $this->_object);
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCall($method)
    {
        $this->_request
            ->expects($this->at(0))
            ->method('getMethod')
            ->will($this->returnValue($method));
        $this->_request
            ->expects($this->at(1))
            ->method('getMediaType')
            ->will($this->returnValue('application/json'));

        $this->_environment
            ->expects($this->at(0))
            ->method('offsetGet')
            ->with($this->equalTo('slim.input'))
            ->will($this->returnValue('{"foo":"bar"}'));
        $this->_environment
            ->expects($this->at(1))
            ->method('offsetSet')
            ->with(
                $this->equalTo('slim.input'),
                $this->equalTo((object) ['foo' => 'bar'])
            );

        $this->_infoPresenter
            ->expects($this->never())
            ->method('process');

        $this->_next
            ->expects($this->once())
            ->method('call');

        $this->_object->call();
    }

    public function dataProviderForTestCall()
    {
        return [
            ['POST'],
            ['PUT'],
        ];
    }

    /**
     * @dataProvider dataProviderForTestCallWithoutBody
     */
    public function testCallWithoutBody($method)
    {
        $this->_request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method));
        $this->_request
            ->expects($this->never())
            ->method('getMediaType');

        $this->_infoPresenter
            ->expects($this->never())
            ->method('process');

        $this->_next
            ->expects($this->once())
            ->method('call');

        $this->_object->call();
    }

    public function dataProviderForTestCallWithoutBody()
    {
        return [
            ['GET'],
            ['DELETE'],
        ];
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCallWithInvalidBody($method)
    {
        $this->_request
            ->expects($this->at(0))
            ->method('getMethod')
            ->will($this->returnValue($method));
        $this->_request
            ->expects($this->at(1))
            ->method('getMediaType')
            ->will($this->returnValue('application/json'));

        $this->_environment
            ->expects($this->once())
            ->method('offsetGet')
            ->with($this->equalTo('slim.input'))
            ->will($this->returnValue('{"foo":"bar"'));
        $this->_environment
            ->expects($this->never())
            ->method('offsetSet');

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(400),
                $this->equalTo('JSON Parse Error')
            );

        $this->_next
            ->expects($this->never())
            ->method('call');

        $this->_object->call();
    }

    /**
     * @dataProvider dataProviderForTestCall
     */
    public function testCallWithInvalidMediaType($method)
    {
        $this->_request
            ->expects($this->at(0))
            ->method('getMethod')
            ->will($this->returnValue($method));
        $this->_request
            ->expects($this->at(1))
            ->method('getMediaType')
            ->will($this->returnValue('application/xml'));

        $this->_environment
            ->expects($this->never())
            ->method('offsetGet');

        $this->_infoPresenter
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo(415),
                $this->equalTo('Only JSON is allowed')
            );

        $this->_next
            ->expects($this->never())
            ->method('call');

        $this->_object->call();
    }
}
