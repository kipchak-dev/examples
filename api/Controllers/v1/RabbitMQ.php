<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\RabbitMQ\RabbitMQ as RabbitMQDriver;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class RabbitMQ extends Controllers\Base
{

    public function publish(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Loading RabbitMQ');

        $rmq  = RabbitMQDriver::get('queue-name-1');

        $message = 'Message published @ ' . date('Y-m-d H:i:s');
        $rmq->publish($message);

        return Http\Response::json($response,
            [
                'published' => $message,
            ],
            200
        );
    }

    public function subscribe(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // NOTE: Please do NOT use the subscribe method in an API in production. It needs to go in a message subscriber / daemon , not an HTTP endppoint.
        // Load this endpoint in your browser first
        // Then each time you load the publish endpoint, you will see the message logged in the container's console.
        $this->logger->debug('Loading RabbitMQ');

        $rmq  = RabbitMQDriver::get('queue-name-1');

        $rmq->subscribe(function (AMQPMessage $message) use ($response) {
            $text = $message->getBody();
            $this->logger->debug('Message received: ' . $text);
            $message->ack();
        });

        // Subscribe will block until the connection is closed so we won't get here.
        return Http\Response::json($response,
            [
                'subscribed' => 'and waiting...',
            ],
            200
        );

    }

}