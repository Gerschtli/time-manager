<?php

use Slim\PDO\Database;
use TimeManager\Decorator\Error as ErrorDecorator;

$app->errorDecorator = function () use ($app) {
    return new ErrorDecorator($app);
};

$app->container->singleton('pdo', function () use ($app) {
    $dsn = 'mysql:host=localhost;dbname=time-manager;charset=utf8';
    $username = 'root';
    $password = 'root';

    return new Database($dsn, $username, $password);
});
