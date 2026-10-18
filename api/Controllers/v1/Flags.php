<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\FeatureFlags\FeatureFlags;
use Kipchak\Driver\FeatureFlags\Request\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Flags extends Controllers\Base
{

    /**
     * Evaluates the sample flags in config/feature-flags.json for a user: GET /v1/flags?user=customer-42&plan=pro
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $query = $request->getQueryParams();
        $user = is_string($query['user'] ?? null) ? $query['user'] : null;
        $plan = is_string($query['plan'] ?? null) ? $query['plan'] : null;

        // Build the context once per request: who the flags are being evaluated for.
        $context = new Context(userId: $user, attributes: $plan === null ? [] : ['plan' => $plan]);

        $flags = FeatureFlags::get('default');

        // A flag check never fails the request: if the flag service is down, the default is returned.
        return Http\Response::json($response,
            [
                'user' => $user,
                'plan' => $plan,
                'flags' => [
                    'new-checkout' => [
                        'enabled' => $flags->isEnabled('new-checkout', $context),
                        'variant' => $flags->variant('new-checkout', $context),
                        'value'   => $flags->value('new-checkout', null, $context),
                    ],
                    'beta-reports' => $flags->isEnabled('beta-reports', $context),
                    'kill-switch'  => $flags->isEnabled('kill-switch', $context),
                ],
            ],
            200
        );
    }

}
