<?php

// Should be set to 0 in production
error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');

//Convert all warnings and errors to Exceptions
function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('exception_error_handler');


// Settings
$settings = [];

$settings['db'] = [
    'host' => getenv('DATABASE_HOST') ? getenv('DATABASE_HOST') : 'localhost',
    'port' => getenv('DATABASE_PORT') ? getenv('DATABASE_PORT') : '5432',
    'user' => getenv('DATABASE_USER') ? getenv('DATABASE_USER') : 'postgres',
    'pw'   => getenv('DATABASE_PW')   ? getenv('DATABASE_PW')   : 'postgrespw'
];

$settings['redis'] = [
    'host' => getenv('REDIS_HOST') ? getenv('REDIS_HOST') : 'localhost',
    'port' => getenv('REDIS_PORT') ? getenv('REDIS_PORT') : '6379'
];


return $settings;