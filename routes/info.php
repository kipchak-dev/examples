<?php
use Api\Controllers;

/**
 * @var \Slim\App $app
 */
$app->get('/info',
    [
        Controllers\Info::class,
        'get'
    ]
);

