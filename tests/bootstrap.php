<?php

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
}
if (!defined('APPLICATION_ENV')) {
    define(
        'APPLICATION_ENV',
        getenv('APPLICATION_ENV') ?: 'development'
    );
}

require_once(PROJECT_ROOT . '/vendor/autoload.php');
