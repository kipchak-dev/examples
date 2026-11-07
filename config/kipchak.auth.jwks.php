<?php

use function Kipchak\Core\env;

return [
    'enabled' => false, // to enable this globally
    'ignore_options' => true, // Setting this to true will enable preflight requests with the OPTIONS HTTP method without authentication
    'ignore_paths' => [
        '/status'
    ],
    'jwksUri' => env('JWKS_URI', 'https://idp.example.com/realms/example/protocol/openid-connect/certs'),
    // Required since 1.41: the exact iss of the tokens you accept, and the client IDs they may be issued to (aud or azp).
    'issuer' => env('JWKS_ISSUER', 'https://idp.example.com/realms/example'),
    'audience' => array_filter(explode(',', env('JWKS_AUDIENCE', 'example-client'))),
    'token_type' => 'Bearer', // refuses Keycloak ID tokens
    'validate_scopes' => true, // If enabled, every scope below is required unless custom ones are passed to the AuthJWKS Middleware
    'scopes' => [
        'email',
        'profile'
    ],
    // Header checked here is "Bearer: "
];