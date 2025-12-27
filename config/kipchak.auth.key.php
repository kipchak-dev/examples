<?php

return [
    'enabled' => false, // Will check for key in key query parameter (?key=xxxxxxx) or x-api-key header globally
    'ignore_options' => true, // Setting this to true will enable preflight requests with the OPTIONS HTTP method without authentication
    'ignore_paths' => [
        '/status'
    ],
    'authorised_keys' => [
        // This format is chosen because it's faster than array with just keys. The values key1, key2 or key3 will be checked
        'key1' => 'client1',
        'key2' => 'client2',
        'key3' => 'client3'
    ],
    'header' => 'apikey',
    'query_param' => 'apikey'
];