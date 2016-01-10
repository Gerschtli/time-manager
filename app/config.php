<?php

$mysql = (object)[
    'database' => '<$= database.name $>',
    'host'     => '<$= database.host $>',
    'username' => '<$= database.user $>',
    'password' => '<$= database.password $>',
];

if (APPLICATION_ENV == 'development') {
    $mysql = (object)[
        'database' => 'time-manager',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'root',
    ];
}

return (object)[
    'mysql' => $mysql,
];
