<?php

namespace TimeManager\Decorator;

use Slim\Http\Response;

class Error extends Base
{
    const STATUS_UNSUPPORTED_MEDIA_TYPE  = 415;
    const STATUS_UNPROCESSABLE_ENTITY    = 422;
    const MESSAGE_UNSUPPORTED_MEDIA_TYPE = 'only JSON is allowed';
    const MESSAGE_UNPROCESSABLE_ENTITY   = 'invalid JSON';

    public function process($errorCode)
    {
        $this->_app->status($errorCode);

        $message = $this->_generateMessage(
            $errorCode,
            $this->_getMessage($errorCode)
        );
        
        $output = [
            'error' => [
                'code'        => $errorCode,
                'description' => $message,
            ]
        ];
        $this->_print($output);
    }

    private function _generateMessage($errorCode, $message = '')
    {
        $httpMessage = Response::getMessageForCode($errorCode);
        $httpMessage = substr($httpMessage, 4);

        if ($message != '') {
            $result = $httpMessage . ', ' . $message;
        } else {
            $result = $httpMessage;
        }

        return $result;
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
