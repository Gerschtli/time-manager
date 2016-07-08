<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use TimeManager\Controller\Error as ErrorController;
use TimeManager\Controller\Task as TaskController;
use TimeManager\Middleware\JsonConverter;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Service\Task as TaskService;
use TimeManager\Service\Time as TimeService;

$app->container->singleton(
    'config',
    function () {
        return require(PROJECT_ROOT . '/app/config.php');
    }
);

$app->container->singleton(
    'controllerError',
    function () use ($app) {
        return new ErrorController($app->presenterInfo);
    }
);

$app->container->singleton(
    'controllerTask',
    function () use ($app) {
        return new TaskController($app);
    }
);

$app->container->singleton(
    'entityManager',
    function () use ($app) {
        $config = Setup::createAnnotationMetadataConfiguration([PROJECT_ROOT . '/lib/Model'], true);

        $mysqlConfig = $app->config->mysql;
        $connectionDetails = [
            'driver'   => 'pdo_mysql',
            'host'     => $mysqlConfig->host,
            'user'     => $mysqlConfig->username,
            'password' => $mysqlConfig->password,
            'dbname'   => $mysqlConfig->database,
        ];

        return EntityManager::create($connectionDetails, $config);
    }
);

$app->container->singleton(
    'middlewareJsonConverter',
    function () {
        return new JsonConverter();
    }
);

$app->container->singleton(
    'presenterData',
    function () use ($app) {
        return new DataPresenter($app);
    }
);

$app->container->singleton(
    'presenterInfo',
    function () use ($app) {
        return new InfoPresenter($app);
    }
);

$app->container->singleton(
    'serviceTask',
    function () use ($app) {
        return new TaskService($app);
    }
);

$app->container->singleton(
    'serviceTime',
    function () use ($app) {
        return new TimeService($app);
    }
);
