<?php

use function Kipchak\Core\env;

return [
    'connections' => [
        'default' => [
            // One or more servers, tried in order on connecting and on reconnecting. Use tls:// to require TLS.
            'servers' => [env('NATS_URL', 'nats://nats:4222')],

            // Shown in the server's connection list (nats server report connections).
            'name' => 'kipchak-starter',

            // Authentication: at most one of username/password, token, nkey_seed or credentials (a .creds file with
            // a user JWT and NKey seed, as issued by nsc). NKeys and credentials need ext-sodium.
            // 'username' => env('NATS_USER', ''),
            // 'password' => env('NATS_PASSWORD', ''),
            // 'token' => env('NATS_TOKEN', ''),
            // 'nkey_seed' => env('NATS_NKEY_SEED', ''),
            // 'credentials' => '/run/secrets/nats.creds',

            // Seconds to connect, and to wait for each request, JetStream publish or API call.
            'timeout' => 2,

            // When the connection drops, how many times to try to reconnect (across all servers) before an
            // operation fails, and the seconds between attempts. 0 fails at once.
            'reconnect_attempts' => 3,
            'reconnect_wait' => 0.1,

            // JetStream streams, created or updated with $nats->jetStream()->ensureStream('VISITS'). Durations are
            // in seconds; other JetStream fields go under 'options'.
            'streams' => [
                'VISITS' => [
                    'subjects' => ['visits.>'],
                    'storage' => 'file',          // file or memory
                    'max_age' => 86400,           // keep messages for a day
                ],
            ],

            // Key-value buckets, used with $nats->keyValue('visits'); created on the first write.
            'buckets' => [
                'visits' => [
                    'history' => 1,               // revisions kept per key (1 to 64)
                ],
            ],
        ],
    ],
];
