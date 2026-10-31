<?php

use Api\Controllers;
use Kipchak\Middleware\Auth\Key\Handlers\ApiKey;
use Kipchak\Middleware\FeatureFlags\Handlers\Gate;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

/**
 * @var App $app
 */
$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/beta',
        [
            Controllers\v1\Beta::class,
            'get'
        ]
    );

    // Slim runs the last-added middleware first: the API key is checked, then the gate answers 404 to keys outside
    // the 'early-access' rollout (key1 and key2 are inside it, key3 is not).
})->add(new Gate('early-access'))->add(new ApiKey($app->getContainer()));
