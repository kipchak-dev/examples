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

class Status extends CoreControllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Checking Status...');

        return Http\Response::json($response,
            [
                'status' => 'alive'
            ],
            200,
            true,
            86400,
        );
    }

}
