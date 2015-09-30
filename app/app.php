<?php

require_once 'dic.php';

use TimeManager\Middleware\JsonConverter;

$app->add(new JsonConverter());

$app->post('/task', [$app->taskController, 'addAction']);
