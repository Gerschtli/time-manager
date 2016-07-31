<?php

namespace TimeManager\Controller;

use Exception;
use Monolog\Logger;
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
    private $_logger;

    public function __construct(Info $infoPresenter, Response $response, Logger $logger)
    {
        $this->_infoPresenter = $infoPresenter;
        $this->_response      = $response;
        $this->_logger        = $logger;
    }

    public function errorAction(Request $request, Response $response, Exception $exception)
    {
        $this->_logger->error('Exception occured', ['exception' => $exception]);

        return $this->_infoPresenter->render(
            $this->_response,
            Presenter::STATUS_INTERNAL_SERVER_ERROR,
            $exception->getMessage()
        );
    }

    public function notFoundAction(Request $request, Response $response)
    {
        $this->_logger->info('Not found');

        return $this->_infoPresenter->render(
            $this->_response,
            Presenter::STATUS_NOT_FOUND,
            Presenter::DESCRIPTION_NO_ROUTE_MATCHED
        );
    }

    public function notAllowedAction(Request $request, Response $response, array $methods)
    {
        $this->_logger->info('Method not allowed', ['methods' => $methods]);

        return $this->_infoPresenter->render(
            $this->_response,
            Presenter::STATUS_METHOD_NOT_ALLOWED,
            sprintf(
                Presenter::DESCRIPTION_AVAILABLE_METHODS,
                implode(', ', $methods)
            )
        );
    }
}
