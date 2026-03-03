<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\ClouDns\ClouDns as ClouDnsDriver;
use Kipchak\Driver\ClouDns\Result\ZoneResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class ClouDns extends Controllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Loading ClouDNS zones');

        $client = ClouDnsDriver::get('default');

        $zones = $client->listZones();

        /** @var ZoneResult $zone */
        $zx = array_map(fn(ZoneResult $zone) => [
            'name'   => $zone->name,
            'type'   => $zone->type->value,
            'status' => $zone->status->value,
        ], $zones);

        return Http\Response::json($response,
            [
                'zones' => $zx,
            ],
            200
        );
    }

}
