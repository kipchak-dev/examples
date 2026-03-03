<?php

use Api\Controllers;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

/**
 * @var App $app
 */
$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/cloudns',
        [
            Controllers\v1\ClouDns::class,
            'get'
        ]
    );

});
