<?php

require PROJECT_ROOT . '/app/dic.php';

$app->add($app->middlewareJsonConverter);

$app->post('/task', [$app->controllerTask, 'addAction']);
