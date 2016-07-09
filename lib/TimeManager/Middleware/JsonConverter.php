<?php

namespace TimeManager\Middleware;

use Slim\Environment;
use Slim\Http\Request;
use Slim\Middleware;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Presenter\Presenter;

class JsonConverter extends Middleware
{
    private $_environment;
    private $_infoPresenter;
    private $_request;

    public function __construct(
        Environment $environment, infoPresenter $infoPresenter, Request $request
    )
    {
        $this->_environment   = $environment;
        $this->_infoPresenter = $infoPresenter;
        $this->_request       = $request;
    }

    public function call()
    {
        if (in_array($this->_request->getMethod(), ['GET', 'DELETE'])) {
            $this->next->call();
        } else {
            if ($this->_request->getMediaType() == 'application/json') {
                $result = json_decode($this->_environment['slim.input']);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->_environment['slim.input'] = $result;
                    $this->next->call();
                } else {
                    $this->_infoPresenter->process(
                        Presenter::STATUS_BAD_REQUEST,
                        Presenter::DESCRIPTION_PARSE_ERROR
                    );
                }
            } else {
                $this->_infoPresenter->process(
                    Presenter::STATUS_UNSUPPORTED_MEDIA_TYPE,
                    Presenter::DESCRIPTION_ONLY_JSON
                );
            }
        }
    }
}
