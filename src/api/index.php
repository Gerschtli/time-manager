<?php

use Slim\App;

require_once(__DIR__ . '/../../vendor/autoload.php');

$settings = require_once(__DIR__ . '/../../app/settings.php');
$app      = new App($settings);

require_once(__DIR__ . '/../../app/dependencies.php');

require_once(__DIR__ . '/../../app/middleware.php');

require_once(__DIR__ . '/../../app/routes.php');

$app->run();
