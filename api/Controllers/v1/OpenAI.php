<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\OpenAI\OpenAI as OpenAIDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class OpenAI extends Controllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Calling OpenAI');

        $client = OpenAIDriver::get('default');

        // model is injected automatically from default_model in kipchak.openai.php
        $result = $client->chat()->create([
            'messages' => [
                ['role' => 'user', 'content' => 'Name one Mamluk Sultan in a single sentence.'],
            ],
        ]);

        return Http\Response::json($response,
            [
                'response' => $result->choices[0]->message->content,
            ],
            200
        );
    }

}
