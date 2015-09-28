<?php

use Slim\PDO\Database;
use TimeManager\Decorator\Error as ErrorDecorator;

$app->container->singleton('config', function () use ($app) {
    $filePath = PROJECT_ROOT . '/app/config/' . APPLICATION_ENV . '.php';
    return file_exists($filePath) ? include $filePath : new stdClass;
});

$app->errorDecorator = function () use ($app) {
    return new ErrorDecorator($app);
};

$app->container->singleton('pdo', function () use ($app) {
    $config = $app->config;

    return new Database(
        "mysql:host={$config->mysql->host};dbname={$config->mysql->database};charset=utf8",
        $config->mysql->username,
        $config->mysql->password
    );
});
