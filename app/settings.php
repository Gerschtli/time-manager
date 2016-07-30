<?php

$mode = getenv('APPLICATION_ENV') ?: 'production';

$database = [
    'database' => '<$= database.name $>',
    'host'     => '<$= database.host $>',
    'username' => '<$= database.user $>',
    'password' => '<$= database.password $>',
];

if ($mode == 'development') {
    $database = [
        'database' => 'time-manager',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'root',
    ];
}

return [
    'settings' => [
        'database' => $database,
        'mode'     => $mode,
    ],
];
