<?php

namespace TimeManager\Decorator;

class Error extends Base
{
    const STATUS_NOT_FOUND               = 404;
    const STATUS_UNSUPPORTED_MEDIA_TYPE  = 415;
    const STATUS_UNPROCESSABLE_ENTITY    = 422;
    const MESSAGE_UNSUPPORTED_MEDIA_TYPE = 'only JSON is allowed';
    const MESSAGE_UNPROCESSABLE_ENTITY   = 'invalid JSON';
    const MESSAGE_INVALID_DATA           = 'invalid data';

    public function process($code, $message = '')
    {
        $description = $this->_generateMessage(
            $code,
            ($message ?: $this->_getMessage($code))
        );

        $output = $this->_generateOutput('error', $code, $description);
        $this->_print($code, $output);
    }

    private function _getMessage($errorCode)
    {
        switch ($errorCode) {
            case self::STATUS_UNSUPPORTED_MEDIA_TYPE:
                $message = self::MESSAGE_UNSUPPORTED_MEDIA_TYPE;
                break;
            case self::STATUS_UNPROCESSABLE_ENTITY:
                $message = self::MESSAGE_UNPROCESSABLE_ENTITY;
                break;
            default:
                $message = '';
                break;
        }
        return $message;
    }
}
