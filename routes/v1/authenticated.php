<?php

use Api\Controllers;
use Kipchak\Middleware\Auth\JWKS\Handlers\AuthJWKS;
use Kipchak\Middleware\Auth\Key\Handlers\ApiKey;
use Slim\Routing\RouteCollectorProxy;

$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/authJWKS',
        [
            Controllers\v1\Authenticated::class,
            'getJWKS'
        ]
    );

})->add(new AuthJWKS($app->getContainer(), ['email']));

$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/authKey',
        [
            Controllers\v1\Authenticated::class,
            'getKey'
        ]
    );

})->add(new ApiKey($app->getContainer()));