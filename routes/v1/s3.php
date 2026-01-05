<?php

use Api\Controllers;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

/**
 * @var App $app
 */
$app->group('/v1', function(RouteCollectorProxy $group) {

    $group->get('/s3',
        [
            Controllers\v1\S3::class,
            'get'
        ]
    );

});