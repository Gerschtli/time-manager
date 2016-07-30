<?php

namespace TimeManager\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class JsonConverter
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $request->registerMediaTypeParser(
            'application/json',
            function ($input) {
                return json_decode($input);
            }
        );

        return $next($request, $response);
    }
}
