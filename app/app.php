<?php

require PROJECT_ROOT . '/app/dic.php';

$app->add($app->middlewareJsonConverter);

$app->post('/task', function () use ($app) {
    $app->controllerTask->addAction();
});
