<?php

use Api\Controllers;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

/**
 * @var App $app
 */
$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/rabbitmq/publish',
        [
            Controllers\v1\RabbitMQ::class,
            'publish'
        ]
    );

    $group->get('/rabbitmq/subscribe',
        [
            Controllers\v1\RabbitMQ::class,
            'subscribe'
        ]
    );

});