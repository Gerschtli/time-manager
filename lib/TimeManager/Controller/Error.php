<?php

namespace TimeManager\Controller;

use Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use TimeManager\Presenter\Info;
use TimeManager\Presenter\Presenter;

/**
 * @SuppressWarnings(PMD.UnusedFormalParameter)
 */
class Error
{
    private $_infoPresenter;
    private $_response;

    public function __construct(Info $infoPresenter, Response $response)
    {
        $this->_infoPresenter = $infoPresenter;
        $this->_response      = $response;
    }

    public function errorAction(Request $request, Response $response, Exception $exception)
    {
        return $this->_infoPresenter->render(
            $this->_response,
            Presenter::STATUS_INTERNAL_SERVER_ERROR,
            $exception->getMessage()
        );
    }

    public function notFoundAction(Request $request, Response $response)
    {
        return $this->_infoPresenter->render(
            $this->_response,
            Presenter::STATUS_NOT_FOUND,
            Presenter::DESCRIPTION_NO_ROUTE_MATCHED
        );
    }

    public function notAllowedAction(Request $request, Response $response, array $methods)
    {
        return $this->_infoPresenter->render(
            $this->_response,
            Presenter::STATUS_NOT_FOUND,
            Presenter::DESCRIPTION_NO_ROUTE_MATCHED
        );
    }
}
