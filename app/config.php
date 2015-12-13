<?php

// TODO: Live-Daten eintragen
$mysql = (object)[
    'database' => '?',
    'host'     => '?',
    'username' => '?',
    'password' => '?',
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
