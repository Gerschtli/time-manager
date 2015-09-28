<?php

use Slim\Slim;

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/../..'));
}
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'production');
}

require PROJECT_ROOT . '/vendor/autoload.php';

$app = new Slim([
    'debug' => false,
]);

require PROJECT_ROOT . '/app/app.php';

$app->run();
