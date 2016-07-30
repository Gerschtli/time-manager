<?php

namespace TimeManager\Presenter;

use Slim\Http\Response;

class Info extends Presenter
{
    public function process(Response $response, $code, $description)
    {
        $body = (object) [
            'code'    => $code,
            'message' => $this->_getMessage($response, $code),
        ];
        if (!empty($description)) {
            $body->description = $description;
        }
        return $response->withJson($body, $code);
    }

    private function _getMessage(Response $response, $code)
    {
        return $response
            ->withStatus($code)
            ->getReasonPhrase();
    }
}
