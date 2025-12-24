<?php

use Api\Controllers;
use Kipchak\Core\Components\Middlewares\AuthJwks;
use Kipchak\Core\Components\Middlewares\AuthKey;
use Slim\Routing\RouteCollectorProxy;

$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/authJWKS',
        [
            Controllers\v1\Authenticated::class,
            'getJWKS'
        ]
    );

})->add(new AuthJwks($app->getContainer(), ['email']));

$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/authKey',
        [
            Controllers\v1\Authenticated::class,
            'getKey'
        ]
    );

})->add(new AuthKey($app->getContainer()));