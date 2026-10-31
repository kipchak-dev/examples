<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\ClickHouse\ClickHouse as ClickHouseDriver;
use Kipchak\Driver\ClickHouse\Exception\ClickHouseConnectionException;
use Kipchak\Driver\ClickHouse\Exception\ClickHouseException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class ClickHouse extends Controllers\Base
{

    private const array SULTANS = ['Baybars', 'Qalawun', 'Qutuz', 'Barquq', 'Qaitbay'];

    /**
     * Records a visit to a sultan, then counts the visits to each: GET /v1/clickhouse?sultan=Baybars
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $sultan = $request->getQueryParams()['sultan'] ?? null;
        $sultan = in_array($sultan, self::SULTANS, true) ? $sultan : self::SULTANS[array_rand(self::SULTANS)];

        $clickhouse = ClickHouseDriver::get('default');

        try {
            $clickhouse->execute('CREATE TABLE IF NOT EXISTS visits (at DateTime, sultan LowCardinality(String)) ENGINE = MergeTree ORDER BY at');

            $clickhouse->insert('visits', [['at' => new \DateTimeImmutable(), 'sultan' => $sultan]]);

            // Values travel as typed parameters, separately from the SQL, so they cannot change the query.
            $result = $clickhouse->select(
                'SELECT sultan, count() AS visits FROM visits WHERE at >= {from:DateTime} GROUP BY sultan ORDER BY visits DESC',
                ['from' => new \DateTimeImmutable('-1 day')],
            );
        } catch (ClickHouseConnectionException $e) {
            // No host could be reached, or the query timed out
            $this->logger->error('ClickHouse unavailable', ['error' => $e->getMessage()]);

            return Http\Response::json($response, ['error' => 'ClickHouse is unavailable'], 503);
        } catch (ClickHouseException $e) {
            return Http\Response::json($response, ['error' => $e->getMessage(), 'code' => $e->errorName], 500);
        }

        return Http\Response::json($response,
            [
                'recorded' => $sultan,
                'visits_last_24_hours' => $result->rows(),
                'rows_read' => $result->rowsRead,
            ],
            200
        );
    }

}
