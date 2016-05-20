<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;

class Error extends Base
{
    const STATUS_BAD_REQUEST            = 400;
    const STATUS_NOT_FOUND              = 404;
    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
    const STATUS_UNPROCESSABLE_ENTITY   = 422;

    const DESCRIPTION_INVALID_STRUCTURE = 'JSON is in invalid data structure';
    const DESCRIPTION_NONEXISTING_KEY   = 'No Data with provided Key found';
    const DESCRIPTION_ONLY_JSON         = 'Only JSON is allowed';
    const DESCRIPTION_PARSE_ERROR       = 'JSON Parse Error';

    public function process($code, $description)
    {
        $body = [
            'code'        => $code,
            'message'     => $this->_getMessage($code),
            'description' => $description,
        ];
        $this->_print($code, $body);
    }

    private function _getMessage($code)
    {
        $message = Response::getMessageForCode($code);
        $message = substr($message, 4);

        return $message;
    }
}
