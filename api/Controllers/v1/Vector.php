<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\Vector\Exception\VectorConnectionException;
use Kipchak\Driver\Vector\Exception\VectorException;
use Kipchak\Driver\Vector\Query\Filter;
use Kipchak\Driver\Vector\Result\Point;
use Kipchak\Driver\Vector\Result\ScoredPoint;
use Kipchak\Driver\Vector\Vector as VectorDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Vector extends Controllers\Base
{

    /**
     * Toy three-dimensional "embeddings" of some sultans: how much each was known as [a soldier, a builder, a diplomat].
     * A real application gets its vectors from an embedding model; with 'embeddings' set in config/kipchak.vector.php,
     * upsertTexts() and searchText() do that for you.
     */
    private const array SULTANS = [
        'baybars' => [[0.9, 0.4, 0.5], ['name' => 'Baybars', 'dynasty' => 'Bahri', 'reigned' => 1260]],
        'qutuz'   => [[1.0, 0.1, 0.2], ['name' => 'Qutuz', 'dynasty' => 'Bahri', 'reigned' => 1259]],
        'qalawun' => [[0.7, 0.8, 0.4], ['name' => 'Qalawun', 'dynasty' => 'Bahri', 'reigned' => 1279]],
        'barquq'  => [[0.6, 0.6, 0.6], ['name' => 'Barquq', 'dynasty' => 'Burji', 'reigned' => 1382]],
        'qaitbay' => [[0.5, 1.0, 0.7], ['name' => 'Qaitbay', 'dynasty' => 'Burji', 'reigned' => 1468]],
    ];

    /**
     * Finds the sultans closest to a profile, optionally from one dynasty: GET /v1/vector?soldier=1&builder=0.2&diplomat=0.2&dynasty=Bahri
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $query = $request->getQueryParams();
        $profile = [
            (float) ($query['soldier'] ?? 1),
            (float) ($query['builder'] ?? 0),
            (float) ($query['diplomat'] ?? 0),
        ];
        $dynasty = is_string($query['dynasty'] ?? null) ? $query['dynasty'] : null;

        $vectors = VectorDriver::get('default');

        try {
            // The same calls work on Qdrant, pgvector and OpenSearch: change the connection's 'store'.
            $vectors->ensureCollection('sultans', 3);
            $vectors->upsert('sultans', array_map(
                fn(string $id) => new Point($id, self::SULTANS[$id][0], self::SULTANS[$id][1]),
                array_keys(self::SULTANS),
            ));

            $matches = $vectors->search('sultans', $profile, limit: 3,
                filter: $dynasty === null ? null : Filter::equals('dynasty', $dynasty),
            );
        } catch (VectorConnectionException $e) {
            $this->logger->error('Vector store unavailable', ['error' => $e->getMessage()]);

            return Http\Response::json($response, ['error' => 'The vector store is unavailable'], 503);
        } catch (VectorException $e) {
            return Http\Response::json($response, ['error' => $e->getMessage()], 500);
        }

        return Http\Response::json($response,
            [
                'profile' => ['soldier' => $profile[0], 'builder' => $profile[1], 'diplomat' => $profile[2]],
                'closest' => array_map(fn(ScoredPoint $match) => [
                    'sultan' => $match->payload['name'],
                    'dynasty' => $match->payload['dynasty'],
                    'score' => round($match->score, 4),   // cosine similarity: 1 is identical
                ], $matches),
            ],
            200
        );
    }

}
