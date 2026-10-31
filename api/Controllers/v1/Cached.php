<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Cached extends Controllers\Base
{

    /**
     * A cacheable response: GET /v1/cached. Repeat it within 60 seconds and X-Cache says HIT, with the same time.
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        return Http\Response::json($response,
            [
                'generated_at' => (new \DateTimeImmutable())->format(DATE_RFC3339_EXTENDED),
            ],
            200
        )->withHeader('Cache-Control', 'public, max-age=60');
    }

}
