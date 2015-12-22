<?php

namespace TimeManager\Decorator;

use Slim\Http\Response;
use TimeManager\AppAware;

abstract class Base extends AppAware
{
    protected function _print($code, $message)
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
