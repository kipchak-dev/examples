<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, origin, Content-Type');

require_once (realpath(__DIR__ . '/../vendor/autoload.php'));

use Kipchak\Core\Api;

// Instantiate Slim, load dependencies and middlewares
$app = Api::boot();

$app->addBodyParsingMiddleware();

$app->run();