<?php

$config = new stdClass();

$config->mysql = (object) [
    'database' => '<$= database.name $>',
    'host'     => '<$= database.host $>',
    'username' => '<$= database.user $>',
    'password' => '<$= database.password $>',
];

if (APPLICATION_ENV == 'development') {
    $config->mysql = (object) [
        'database' => 'time-manager',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'root',
    ];
}

return $config;
