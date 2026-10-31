<?php

use Api\Controllers;
use Kipchak\Middleware\ResponseCache\Handlers\Cache;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

/**
 * @var App $app
 */
$app->group('/v1', function(RouteCollectorProxy $group) use ($app) {

    // Cached for as long as the response's Cache-Control allows, or 60 seconds if it does not say.
    $group->get('/cached',
        [
            Controllers\v1\Cached::class,
            'get'
        ]
    )->add(new Cache($app->getContainer(), ttl: 60));

});
