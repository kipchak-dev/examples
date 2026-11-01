<?php

use Api\Controllers;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

/**
 * @var App $app
 */
$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/nats',
        [
            Controllers\v1\Nats::class,
            'get'
        ]
    );

});
