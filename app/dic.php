<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use TimeManager\Controller\Task as TaskController;
use TimeManager\Decorator\Data as DataDecorator;
use TimeManager\Decorator\Error as ErrorDecorator;
use TimeManager\Decorator\Success as SuccessDecorator;
use TimeManager\Middleware\JsonConverter;
use TimeManager\Model\Project as ProjectModel;
use TimeManager\Model\Task as TaskModel;
use TimeManager\Model\Time as TimeModel;
use TimeManager\Service\Project as ProjectService;
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
    'dbal',
    function () use ($app) {
        $isDevMode = (APPLICATION_ENV != 'production');
        $config = Setup::createAnnotationMetadataConfiguration([PROJECT_ROOT . '/lib/Model'], $isDevMode);

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
    'decoratorData',
    function () use ($app) {
        return new DataDecorator($app);
    }
);

$app->container->singleton(
    'decoratorError',
    function () use ($app) {
        return new ErrorDecorator($app);
    }
);

$app->container->singleton(
    'decoratorSuccess',
    function () use ($app) {
        return new SuccessDecorator($app);
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

$app->container->set(
    'modelProject',
    function () {
        return new ProjectModel();
    }
);

$app->container->singleton(
    'serviceProject',
    function () use ($app) {
        return new ProjectService($app);
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
