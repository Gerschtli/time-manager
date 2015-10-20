<?php

namespace TimeManager\Controller;

use Slim\Slim;
use TimeManager\Decorator\Error;
use TimeManager\Decorator\Success;

class Task
{
    private $_app;

    public function __construct(Slim $app) {
        $this->_app = $app;
    }

    public function addAction()
    {
        $data   = $this->_app->request->getBody();
        $result = $this->_app->serviceTask->createModel($data);

        if (!is_null($result)) {
            $this->_app->decoratorSuccess->process(
                Success::STATUS_CREATED
            );
        } else {
            $this->_app->decoratorError->process(
                Error::STATUS_UNPROCESSABLE_ENTITY,
                Error::MESSAGE_INVALID_DATA
            );
        }
    }
}
