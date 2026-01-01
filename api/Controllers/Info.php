<?php

namespace Api\Controllers;

use Kipchak\Core\Components\Controllers as CoreControllers;
use Kipchak\Core\Components\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Info extends CoreControllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return Http\Response::json($response,
            [
                'headers' => $request->getHeaders(),
                'attributes' => $request->getAttributes(),
                'serverParams' => $request->getServerParams(),
                'uri' => $request->getUri(),
                'method' => $request->getMethod(),
                'body' => $request->getBody()->getContents(),
                'cookies' => $request->getCookieParams(),
                'query' => $request->getQueryParams(),
            ],
            200,
            true,
            86400,
        );
    }

}
