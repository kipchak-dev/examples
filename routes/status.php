<?php
use Api\Controllers;

/**
 * @var \Kipchak\Core\Components\App $app
 */
$app->get('/status',
    [
        Controllers\Status::class,
        'get'
    ]
);

