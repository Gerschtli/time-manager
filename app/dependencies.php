<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use TimeManager\Controller\Error as ErrorController;
use TimeManager\Controller\Task as TaskController;
use TimeManager\Middleware\JsonConverter;
use TimeManager\Presenter\Data as DataPresenter;
use TimeManager\Presenter\Info as InfoPresenter;
use TimeManager\Service\Task as TaskService;
use TimeManager\Service\Time as TimeService;
use TimeManager\Transformer\Task as TaskTransformer;
use TimeManager\Util\Date;

$container = $app->getContainer();

$container['errorHandler'] = function ($container) {
    return [
        $container->get(ErrorController::class),
        'errorAction',
    ];
};

$container['notFoundHandler'] = function ($container) {
    return [
        $container->get(ErrorController::class),
        'notFoundAction',
    ];
};

$container['notAllowedHandler'] = function ($container) {
    return [
        $container->get(ErrorController::class),
        'notAllowedAction',
    ];
};

$container['logger'] = function ($container) {
    $settings = $container->get('settings');

    $formatter = new LineFormatter();
    $formatter->includeStacktraces();

    $handler = new StreamHandler(
        $settings['logger']['path'],
        Logger::DEBUG
    );
    $handler->setFormatter($formatter);

    $logger = new Logger($settings['logger']['name']);
    $logger->pushProcessor(new WebProcessor());
    $logger->pushHandler($handler);

    return $logger;
};

$container[ErrorController::class] = function ($container) {
    return new ErrorController(
        $container->get(InfoPresenter::class),
        $container->get('response'),
        $container->get('logger')
    );
};

$container[TaskController::class] = function ($container) {
    return new TaskController(
        $container->get(DataPresenter::class),
        $container->get(InfoPresenter::class),
        $container->get(TaskService::class),
        $container->get(TaskTransformer::class)
    );
};

$container[EntityManager::class] = function ($container) {
    $config = $container->get('settings')['database'];

    return EntityManager::create(
        [
            'driver'   => 'pdo_mysql',
            'host'     => $config['host'],
            'user'     => $config['username'],
            'password' => $config['password'],
            'dbname'   => $config['database'],
        ],
        Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/../lib/TimeManager/Model'],
            true
        )
    );
};

$container[DataPresenter::class] = function ($container) {
    return new DataPresenter();
};

$container[InfoPresenter::class] = function ($container) {
    return new InfoPresenter();
};

$container[JsonConverter::class] = function ($container) {
    return new JsonConverter();
};

$container[TaskService::class] = function ($container) {
    return new TaskService(
        $container->get(EntityManager::class)
    );
};

$container[TimeService::class] = function ($container) {
    return new TimeService(
        $container->get(EntityManager::class)
    );
};

$container[TaskTransformer::class] = function ($container) {
    return new TaskTransformer(
        $container->get(Date::class)
    );
};

$container[Date::class] = function ($container) {
    return new Date();
};
