<?php

require(PROJECT_ROOT . '/app/dic.php');

$app->add($app->middlewareJsonConverter);

$app->post(
    '/task',
    function () use ($app) {
        $app->controllerTask->addAction();
    }
);

$app->get(
    '/task',
    function () use ($app) {
        $app->controllerTask->getAllAction();
    }
);

$app->get(
    '/task/:taskid',
    function ($taskId) use ($app) {
        $app->controllerTask->getAction($taskId);
    }
);

$app->delete(
    '/task/:taskid',
    function ($taskId) use ($app) {
        $app->controllerTask->deleteAction($taskId);
    }
);

$app->notFound(
    function () use ($app) {
        echo $app->controllerError->notFoundAction();
    }
);

$app->error(
    function () use ($app) {
        echo $app->controllerError->errorAction();
    }
);
