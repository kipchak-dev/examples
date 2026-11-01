<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\NATS\Exception\NatsConnectionException;
use Kipchak\Driver\NATS\Exception\NatsException;
use Kipchak\Driver\NATS\Exception\NoRespondersException;
use Kipchak\Driver\NATS\Exception\RevisionMismatchException;
use Kipchak\Driver\NATS\NATS as NATSDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Nats extends Controllers\Base
{

    private const array SULTANS = ['Baybars', 'Qalawun', 'Qutuz', 'Barquq', 'Qaitbay'];

    /**
     * Stores a visit to a sultan in a JetStream stream, then counts it in a key-value bucket: GET /v1/nats?sultan=Baybars
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $sultan = $request->getQueryParams()['sultan'] ?? null;
        $sultan = in_array($sultan, self::SULTANS, true) ? $sultan : self::SULTANS[array_rand(self::SULTANS)];

        $nats = NATSDriver::get('default');
        $visit = ['sultan' => $sultan, 'at' => date(DATE_ATOM)];

        try {
            try {
                // Returns once the stream has stored the message.
                $ack = $nats->jetStream()->publish('visits.' . strtolower($sultan), $visit);
            } catch (NoRespondersException) {
                // No stream captures the subject yet: create VISITS from config/kipchak.nats.php, then publish again.
                // A real API would do this in a deployment step or a worker's start-up instead.
                $nats->jetStream()->ensureStream('VISITS');
                $ack = $nats->jetStream()->publish('visits.' . strtolower($sultan), $visit);
            }

            // A counter without a lock: update against the revision read, and read again if someone wrote first.
            $counts = $nats->keyValue('visits');
            do {
                $entry = $counts->get($sultan);
                try {
                    $entry === null ? $counts->create($sultan, 1) : $counts->update($sultan, (int) $entry->value + 1, $entry->revision);
                    break;
                } catch (RevisionMismatchException) {
                }
            } while (true);

            $visits = [];
            foreach (self::SULTANS as $name) {
                $visits[$name] = (int) ($counts->get($name)?->value ?? 0);
            }
        } catch (NatsConnectionException $e) {
            // No server could be reached, or the connection was lost and could not be re-established
            $this->logger->error('NATS unavailable', ['error' => $e->getMessage()]);

            return Http\Response::json($response, ['error' => 'NATS is unavailable'], 503);
        } catch (NatsException $e) {
            return Http\Response::json($response, ['error' => $e->getMessage()], 500);
        }

        return Http\Response::json($response,
            [
                'recorded' => $sultan,
                'stream' => $ack->stream,
                'sequence' => $ack->sequence,
                'visits' => $visits,
            ],
            200
        );
    }

}
