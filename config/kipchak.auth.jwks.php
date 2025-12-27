<?php

return [
    'enabled' => false, // to enable this globally
    'ignore_options' => true, // Setting this to true will enable preflight requests with the OPTIONS HTTP method without authentication
    'ignore_paths' => [
        '/status'
    ],
    'jwksUri' => 'https://auth.islamic.network/auth/realms/islamic-network/protocol/openid-connect/certs',
    'validate_scopes' => true, // If enabled, the following scopes will be validated unless custom ones are passed to the AuthWJKS Middleware
    'scopes' => [
        'email',
        'profile'
    ],
    // Header checked here is "Bearer: "
];