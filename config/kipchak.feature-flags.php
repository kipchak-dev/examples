<?php

use function Kipchak\Core\env;

/**
 * Feature flags from Unleash, Flagsmith or GrowthBook. See https://kipchak.dev/docs/driver-feature-flags for every
 * setting, and install the SDK for each provider you use (this starter ships with Unleash's: unleash/client).
 *
 * With UNLEASH_URL unset, the flags come only from config/feature-flags.json, so GET /v1/flags works without an
 * Unleash server. Set UNLEASH_URL and UNLEASH_TOKEN to use a real one; the file is then used until the first fetch
 * works.
 */
return [
    'connections' => [
        'default' => [
            'provider' => 'unleash',
            'url' => env('UNLEASH_URL', ''),
            'token' => env('UNLEASH_TOKEN', ''),
            'app_name' => 'kipchak-starter',
            'bootstrap_file' => __DIR__ . '/feature-flags.json',

            // Seconds between fetches of the flag definitions, which each worker keeps in memory.
            'refresh_interval' => 30,
            'cache' => 'memory',
        ],

        // 'flagsmith' => [
        //     'provider' => 'flagsmith',                                   // composer require flagsmith/flagsmith-php-client
        //     'environment_key' => env('FLAGSMITH_ENVIRONMENT_KEY', ''),   // a server-side "ser." key
        // ],

        // 'growthbook' => [
        //     'provider' => 'growthbook',                                  // composer require growthbook/growthbook
        //     'client_key' => env('GROWTHBOOK_CLIENT_KEY', ''),
        // ],
    ],
];
