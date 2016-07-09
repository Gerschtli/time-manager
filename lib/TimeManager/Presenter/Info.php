<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;

class Info extends Presenter
{
    public function process($code, $description, $returnPlain = false)
    {
        $body = (object)[
            'code'    => $code,
            'message' => $this->_getMessage($code),
        ];
        if (!empty($description)) {
            $body->description = $description;
        }
        if ($returnPlain) {
            return $this->_encodeBody($body);
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
