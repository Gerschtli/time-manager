<?php

namespace TimeManager\Decorator;

use Slim\Http\Response;

class Error extends Base
{
    const STATUS_NOT_FOUND               = 404;
    const STATUS_UNSUPPORTED_MEDIA_TYPE  = 415;
    const STATUS_UNPROCESSABLE_ENTITY    = 422;
    const MESSAGE_UNSUPPORTED_MEDIA_TYPE = 'only JSON is allowed';
    const MESSAGE_UNPROCESSABLE_ENTITY   = 'invalid JSON';
    const MESSAGE_INVALID_DATA           = 'invalid data';

    public function process($code, $description = '')
    {
        $body = [
            'code'    => $code,
            'message' => $this->_getMessage($code),
        ];
        if ($description != '') {
            $body['description'] = $description;
        }
        $this->_print($code, $body);
    }

    private function _getMessage($code)
    {
        $message = Response::getMessageForCode($code);
        $message = substr($message, 4);

        return $message;
    }
}
