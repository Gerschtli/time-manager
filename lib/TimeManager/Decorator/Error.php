<?php

namespace TimeManager\Decorator;

use Slim\Http\Response;

class Error extends Base
{
    const UNSUPPORTED_MEDIA_TYPE         = 415;
    const UNPROCESSABLE_ENTITY           = 422;
    const UNSUPPORTED_MEDIA_TYPE_MESSAGE = 'only JSON is allowed';
    const UNPROCESSABLE_ENTITY_MESSAGE   = 'invalid JSON';

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
            case self::UNSUPPORTED_MEDIA_TYPE:
                $message = self::UNSUPPORTED_MEDIA_TYPE_MESSAGE;
                break;
            case self::UNPROCESSABLE_ENTITY:
                $message = self::UNPROCESSABLE_ENTITY_MESSAGE;
                break;
            default:
                $message = '';
                break;
        }
        return $message;
    }
}
