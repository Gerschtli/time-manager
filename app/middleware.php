<?php

use TimeManager\Middleware\JsonConverter;

$app->add(
    $app->getContainer()
        ->get(JsonConverter::class)
);
