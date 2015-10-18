<?php

namespace TimeManager\Decorator;

use Slim\Slim;

abstract class Base
{
    protected $_app;

    public function __construct(Slim $app)
    {
        $this->_app = $app;
    }

    protected function _print($message)
    {
        $this->_app->contentType('application/json');
        $this->_app->response->setBody(json_encode($message));
    }
}
