<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Driver\CouchDB\Driver as CouchDB;
use Kipchak\Core\Components\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Couch extends Controllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Checking CouchDB...');

        $c = CouchDB::get('default');

        // Call this if the database has not been created.
        $c->createDatabase();

        // Create document content
        $document = json_encode(
            [
                'Allahu' => 'Akbar',
                'Alhamdu' => 'lillah',
                'Subhan' => 'Allah'
            ]
        );

        $c->create('123', $document);

        $c->update('123', $document);

        // $c->delete('123');
        return Http\Response::json($response,
            [
                'document' => $c->read('123')
            ],
            200
        );
    }

}