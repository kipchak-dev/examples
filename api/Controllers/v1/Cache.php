<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\Filecache\Filecache as FilecacheDriver;
use Kipchak\Driver\Memcached\Memcached as MemcachedDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Cache extends Controllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Loading cache');

        $memcached  = MemcachedDriver::get('cache');

        $fromMemcached = $memcached->get('kipchak', function (ItemInterface $item) {
            $item->expiresAfter(2);

            return 'Ethnic origin of the Mamluks';
        });

        $file = FilecacheDriver::get();

        $fromFile = $file->get('baybars', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return 'The general (and eventually Sultan) who defeated the Mongols at Ayn Jalut';
        });

        return Http\Response::json($response,
            [
                'memcached' => $fromMemcached,
                'file' => $fromFile
            ],
            200
        );
    }

}