<?php

$mode = getenv('APPLICATION_ENV') ?: 'production';

$defaultSettings = [
    'mode'     => $mode,
    'logger'   => [
        'name' => 'Default',
    ],
];

$customSettings = parse_ini_file('parameter.ini');

return [
    'settings' => array_replace_recursive($defaultSettings, $customSettings),
];
