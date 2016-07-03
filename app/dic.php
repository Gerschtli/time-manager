<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use TimeManager\Controller\Task as TaskController;
use TimeManager\Middleware\JsonConverter;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Model\Time as TimeModel;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Error as ErrorPresenter;
use TimeManager\Service\Task as TaskService;
use TimeManager\Service\Time as TimeService;

$app->container->singleton(
    'config',
    function () {
        return require(PROJECT_ROOT . '/app/config.php');
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

$app->container->set(
    'modelTask',
    function () {
        return new TaskModel();
    }
);

$app->container->set(
    'modelTime',
    function () {
        return new TimeModel();
    }
);

$app->container->singleton(
    'presenterData',
    function () use ($app) {
        return new DataPresenter($app);
    }
);

$app->container->singleton(
    'presenterError',
    function () use ($app) {
        return new ErrorPresenter($app);
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
