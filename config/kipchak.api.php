<?php

use function Kipchak\Core\env;

return [
    'name' => 'kipchak-template', // Hyphen or underscore separated string
    'debug' => env('DEBUG', true) == true,
    'logExceptions' => true,
    'logExceptionDetails' => true,
    // Write exceptions through the Logger driver (and so through its processors, such as PII Redaction) instead of
    // PHP's error_log(). Needs kipchak/middleware-error 1.29 or later.
    // 'logExceptionsTo' => 'logger',
    // If debug is enabled, loglevel is debug. Otherwise, it is info. Overwrite it by specifying it below.
    // 'loglevel' => \Monolog\Level::Debug
];