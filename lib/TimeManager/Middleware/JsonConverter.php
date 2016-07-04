<?php

namespace TimeManager\Middleware;

use Slim\Middleware;
use TimeManager\Presenter\Base as Presenter;

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
                $env    = $this->app->environment();
                $result = json_decode($env['slim.input']);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $env['slim.input'] = $result;
                    $this->next->call();
                } else {
                    $this->_processInfo(
                        Presenter::STATUS_BAD_REQUEST,
                        Presenter::DESCRIPTION_PARSE_ERROR
                    );
                }
            } else {
                $this->_processInfo(
                    Presenter::STATUS_UNSUPPORTED_MEDIA_TYPE,
                    Presenter::DESCRIPTION_ONLY_JSON
                );
            }
        }
    }

    private function _processInfo($code, $description)
    {
        $this->app->presenterInfo->process($code, $description);
    }
}
