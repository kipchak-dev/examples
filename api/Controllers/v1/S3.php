<?php

namespace Api\Controllers\v1;

use AsyncAws\S3\ValueObject\Bucket;
use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Kipchak\Driver\S3\S3 as S3Driver;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class S3 extends Controllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Loading S3');

        $s3 = S3Driver::get('default');

        /** @var Bucket $bucket */
        foreach ($s3->listBuckets() as $bucket) {
            $bx[] = $bucket->getName();
        }

        return Http\Response::json($response,
            [
                'buckets' => $bx,
            ],
            200
        );
    }

}