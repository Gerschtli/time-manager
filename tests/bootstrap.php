<?php

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

use Slim\Slim;
use There4\Slim\Test\WebTestCase;

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
}
if (!defined('APPLICATION_ENV')) {
    define(
        'APPLICATION_ENV',
        getenv('APPLICATION_ENV') ?: 'development'
    );
}
require_once PROJECT_ROOT . '/vendor/autoload.php';

class LocalWebTestCase extends WebTestCase
{    
    public function getSlimInstance()
    {
        $app = new Slim([
            'debug' => false,
        ]);

        require PROJECT_ROOT . '/app/app.php';
        return $app;
    }
}
