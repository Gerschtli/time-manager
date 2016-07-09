<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;

abstract class Presenter
{
    const STATUS_OK                     = 200;
    const STATUS_CREATED                = 201;
    const STATUS_ACCEPTED               = 202;
    const STATUS_BAD_REQUEST            = 400;
    const STATUS_NOT_FOUND              = 404;
    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
    const STATUS_UNPROCESSABLE_ENTITY   = 422;
    const STATUS_INTERNAL_SERVER_ERROR  = 500;

    const DESCRIPTION_SUCCESSFUL_DELETION = 'Deletion successful';
    const DESCRIPTION_INVALID_STRUCTURE   = 'JSON is in invalid data structure';
    const DESCRIPTION_NONEXISTING_KEY     = 'No Data with provided Key found';
    const DESCRIPTION_ONLY_JSON           = 'Only JSON is allowed';
    const DESCRIPTION_PARSE_ERROR         = 'JSON Parse Error';
    const DESCRIPTION_NO_ROUTE_MATCHED    = 'No existing Route matched';

    protected $_response;

    public function __construct(Response $response)
    {
        $this->_response = $response;
    }

    protected function _encodeBody($body)
    {
        return json_encode($body);
    }

    protected function _print($code, $body)
    {
        $this->_response->setStatus($code);

        $this->_response->headers->set('Content-Type', 'application/json');
        $this->_response->setBody($this->_encodeBody($body));
    }
}
