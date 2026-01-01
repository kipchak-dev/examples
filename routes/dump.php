<?php
use Api\Controllers;

/**
 * @var \Kipchak\Core\Components\App $app
 */
$app->get('/dump',
    [
        Controllers\Dump::class,
        'get'
    ]
);

