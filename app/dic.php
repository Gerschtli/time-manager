<?php

use TimeManager\Decorator\Error as ErrorDecorator;

$app->errorDecorator = function () use ($app) {
    return new ErrorDecorator($app);
};
