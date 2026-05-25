<?php

use function Kipchak\Core\env;

return [
    'name' => 'kipchak-template', // Hyphen or underscore separated string
    'debug' => env('DEBUG', true) == true,
    'logExceptions' => true,
    'logExceptionDetails' => true,
    // If debug is enabled, loglevel is debug. Otherwise, it is info. Overwrite it by specifying it below.
    // 'loglevel' => \Monolog\Level::Debug
];