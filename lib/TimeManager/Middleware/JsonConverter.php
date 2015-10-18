<?php

namespace TimeManager\Middleware;

use Slim\Middleware;
use TimeManager\Decorator\Error as ErrorDecorator;

class JsonConverter extends Middleware
{
    public function call()
    {
        $request = $this->app->request;
        if (in_array($request->getMethod(), ['GET', 'DELETE'])) {
            $this->next->call();
        } else {
            $mediaType = $request->getMediaType();
            if ($mediaType == 'application/json') {
                $env = $this->app->environment();
                $result = json_decode($env['slim.input']);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $env['slim.input'] = $result;
                    $this->next->call();
                } else {
                    $this->_printError(ErrorDecorator::STATUS_UNPROCESSABLE_ENTITY);
                }
            } else {
                $this->_printError(ErrorDecorator::STATUS_UNSUPPORTED_MEDIA_TYPE);
            }
        }
    }

    private function _printError($errorCode)
    {
        $this->app->errorDecorator->process($errorCode);
    }
}
