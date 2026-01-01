<?php
use Api\Controllers;

/**
 * @var \Kipchak\Core\Components\App $app
 */
$app->get('/info',
    [
        Controllers\Info::class,
        'get'
    ]
);

