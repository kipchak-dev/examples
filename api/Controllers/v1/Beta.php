<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Middleware\FeatureFlags\FeatureFlags;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Beta extends Controllers\Base
{

    /**
     * Only reached by API keys inside the 'early-access' rollout (routes/v1/beta.php): GET /v1/beta?apikey=key1
     * (key1 and key2 are inside it; key3 gets 404)
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        // The request's flags: evaluated once per request, so this agrees with the gate in front of the route.
        $flags = FeatureFlags::of($request);

        return Http\Response::json($response,
            [
                'consumer' => $flags->context()->userId,
                'early-access' => $flags->isEnabled('early-access'),
                'beta-reports' => $flags->isEnabled('beta-reports'),
                'new-checkout' => $flags->isEnabled('new-checkout'),
            ],
            200
        );
    }

}
