<?php

namespace TimeManager\Decorator;

use TimeManager\AppAware;

abstract class Base extends AppAware
{
    protected function _print($code, $body)
    {
        $this->_app->status($code);

        $this->_app->contentType('application/json');
        $this->_app->response->setBody(json_encode($body));
    }
}
