<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use TimeManager\Controller\Task as TaskController;
use TimeManager\Decorator\Error as ErrorDecorator;
use TimeManager\Model\Project as ProjectModel;
use TimeManager\Service\Project as ProjectService;

$app->container->singleton(
    'config',
    function () use ($app) {
        $filePath = PROJECT_ROOT . '/app/config/' . APPLICATION_ENV . '.php';
        return file_exists($filePath) ? include $filePath : new stdClass;
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
        $config    = Setup::createAnnotationMetadataConfiguration([PROJECT_ROOT .'/lib/Model'], $isDevMode);

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
    'decoratorError',
    function () use ($app) {
        return new ErrorDecorator($app);
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
