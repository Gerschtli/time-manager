<?php

use TimeManager\Controller\Task as TaskController;

$app->get(
    '/task',
    TaskController::class . ':getAllAction'
);

$app->post(
    '/task',
    TaskController::class . ':addAction'
);

$app->get(
    '/task/{taskId}',
    TaskController::class . ':getAction'
);

$app->put(
    '/task/{taskId}',
    TaskController::class . ':updateAction'
);

$app->delete(
    '/task/{taskId}',
    TaskController::class . ':deleteAction'
);
