<?php

$app->errorDecorator = function () use ($app) {
    return new \TimeManager\Decorator\Error($app);
};
