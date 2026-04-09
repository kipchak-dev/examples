<?php

// Prevent worker script termination when a client connection is interrupted
ignore_user_abort(true);

require_once (realpath(__DIR__ . '/../vendor/autoload.php'));

use Kipchak\Core\Api;
//use Kipchak\Driver\Doctrine\Doctrine;

// Instantiate Slim, load dependencies and middlewares
$app = Api::boot();

// Add middleware once, outside the loop
$app->addBodyParsingMiddleware();

// Handler outside the loop for better performance (doing less work)
$handler = static function () use ($app) {
    // Reset each Doctrine identity map on every request (FrankenPHP worker mode keeps one EM per process)
    // Doctrine::get('entitymanager.primary')->clear();

    /**
     * Tweak these headers as needed, especially if you are going to use this API from a client side SPA
     * without a Backend for Frontend
     */
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Authorization, origin, if-none-match');
    header('Access-Control-Expose-Headers: *');

    try {
        // Called when a request is received,
        // superglobals, php://input and the like are reset
        $app->run();
    } catch (\Throwable $exception) {
        // `set_exception_handler` is called only when the worker script ends,
        // which may not be what you expect, so catch and handle exceptions here
        http_response_code(500);
        echo $exception->getMessage();
    }
};

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; ++$nbRequests) {
    $keepRunning = \frankenphp_handle_request($handler);

    // Do something after sending the HTTP response
    // $app->terminate();

    // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
    gc_collect_cycles();

    if (!$keepRunning) break;
}

// Cleanup
//$myApp->shutdown();
