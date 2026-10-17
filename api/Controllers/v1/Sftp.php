<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\Sftp\Exception\SftpConnectionException;
use Kipchak\Driver\Sftp\Exception\SftpException;
use Kipchak\Driver\Sftp\Result\FileInfo;
use Kipchak\Driver\Sftp\Sftp as SftpDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Sftp extends Controllers\Base
{

    /**
     * Lists the files in the connection's root directory, optionally filtered with a wildcard: GET /v1/sftp?pattern=*.csv
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Listing files over SFTP');

        $pattern = $request->getQueryParams()['pattern'] ?? null;

        try {
            $files = SftpDriver::get('default')->files('', is_string($pattern) ? $pattern : null);
        } catch (SftpConnectionException $e) {
            // The server could not be reached, its host key did not match, or it rejected the credentials
            $this->logger->error('SFTP server unavailable', ['error' => $e->getMessage()]);

            return Http\Response::json($response, ['error' => 'The SFTP server is unavailable'], 502);
        } catch (SftpException $e) {
            return Http\Response::json($response, ['error' => $e->getMessage()], 500);
        }

        return Http\Response::json($response,
            [
                'files' => array_map(fn(FileInfo $file) => [
                    'path'     => $file->path,
                    'size'     => $file->size,
                    'modified' => $file->modified?->format(DATE_ATOM),
                ], $files),
            ],
            200
        );
    }

}
