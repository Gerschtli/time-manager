<?php

require(PROJECT_ROOT . '/app/dic.php');

$app->add($app->middlewareJsonConverter);


$app->get(
    '/task',
    [$app->controllerTask, 'getAllAction']
);

$app->post(
    '/task',
    [$app->controllerTask, 'addAction']
);

$app->get(
    '/task/:taskId',
    [$app->controllerTask, 'getAction']
);

$app->put(
    '/task/:taskId',
    [$app->controllerTask, 'updateAction']
);

$app->delete(
    '/task/:taskId',
    [$app->controllerTask, 'deleteAction']
);

$app->notFound(
    [$app->controllerError, 'notFoundAction']
);

$app->error(
    [$app->controllerError, 'errorAction']
);
