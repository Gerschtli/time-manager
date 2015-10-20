<?php

namespace TimeManager\Decorator;

use Slim\Http\Response;
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
        $this->_app->status($code);

        $this->_app->contentType('application/json');
        $this->_app->response->setBody(json_encode($message));
    }

    protected function _generateMessage($code, $message = '')
    {
        $httpMessage = Response::getMessageForCode($code);
        $httpMessage = substr($httpMessage, 4);

        if ($message != '') {
            $result = $httpMessage . ', ' . $message;
        } else {
            $result = $httpMessage;
        }

        return $result;
    }
}
