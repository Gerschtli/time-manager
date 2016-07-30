<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;

abstract class Presenter
{
    const STATUS_OK                     = 200;
    const STATUS_CREATED                = 201;
    const STATUS_ACCEPTED               = 202;
    const STATUS_NOT_FOUND              = 404;
    const STATUS_UNPROCESSABLE_ENTITY   = 422;
    const STATUS_INTERNAL_SERVER_ERROR  = 500;

    const DESCRIPTION_SUCCESSFUL_DELETION = 'Deletion successful';
    const DESCRIPTION_INVALID_STRUCTURE   = 'JSON is in invalid data structure';
    const DESCRIPTION_NONEXISTING_KEY     = 'No Data with provided Key found';
    const DESCRIPTION_NO_ROUTE_MATCHED    = 'No existing Route matched';
}
