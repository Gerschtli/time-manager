<?php

namespace TimeManager;

class ErrorTest extends \LocalWebTestCase
{
    public function testErrorAction()
    {
        $this->markTestSkipped('Can not test "Internal Server Error" route.');

        $this->app->controllerError = $this
            ->getMockBuilder('\TimeManager\Controller\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->controllerError
            ->expects($this->once())
            ->method('errorAction');

        $this->client->get('/route');
    }

    public function testNotFoundAction()
    {
        $this->app->controllerError = $this
            ->getMockBuilder('\TimeManager\Controller\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->controllerError
            ->expects($this->once())
            ->method('notFoundAction');

        $this->client->get('/qwsdfgz65redfghji');
    }
}
