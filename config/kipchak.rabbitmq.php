<?php

use function Kipchak\Core\env;

return [
    'hostname' => env('RABBITMQ_HOST', ''),
    'port' => env('RABBITMQ_PORT', 5672),
    'username' => env('RABBITMQ_USERNAME', ''),
    'password' => env('RABBITMQ_PASSWORD', ''),
    'vhost' => env('RABBITMQ_VHOST', '/'),
    'isSecure' => env('RABBITMQ_IS_SECURE', false),
    'sslVerify' => env('RABBITMQ_SSL_VERIFY', false),
    'queues' => [
        'queue-name-1',
        'queue-name-2',
    ]
];