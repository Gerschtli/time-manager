<?php

namespace TimeManager\Presenter;

use TimeManager\AppAware;

abstract class Base extends AppAware
{
    const STATUS_OK                     = 200;
    const STATUS_CREATED                = 201;
    const STATUS_BAD_REQUEST            = 400;
    const STATUS_NOT_FOUND              = 404;
    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
    const STATUS_UNPROCESSABLE_ENTITY   = 422;

    const DESCRIPTION_SUCCESSFUL_DELETION = 'Deletion successful';
    const DESCRIPTION_INVALID_STRUCTURE   = 'JSON is in invalid data structure';
    const DESCRIPTION_NONEXISTING_KEY     = 'No Data with provided Key found';
    const DESCRIPTION_ONLY_JSON           = 'Only JSON is allowed';
    const DESCRIPTION_PARSE_ERROR         = 'JSON Parse Error';

    protected function _print($code, $body)
    {
        $this->_app->status($code);

        $this->_app->contentType('application/json');
        $this->_app->response->setBody(json_encode($body));
    }
}
