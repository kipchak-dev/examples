<?php

namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\Anthropic\Anthropic as AnthropicDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * All Controllers extending Controllers\Base Contain the Service / DI Container as a protected property called $container.
 * Access it using $this->container in your controller.
 * Default objects bundled into a container are:
 * logger - which returns an instance of \Monolog\Logger. This is also a protected property on your controller. Access it using $this->logger.
 */

class Anthropic extends Controllers\Base
{

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $this->logger->debug('Calling Anthropic');

        $client = AnthropicDriver::get('default');

        // model is injected automatically from default_model in kipchak.anthropic.php
        $message = $client->messages()->create(
            messages:  [['role' => 'user', 'content' => 'Name one Mamluk Sultan in a single sentence.']],
            maxTokens: 256,
        );

        return Http\Response::json($response,
            [
                'response' => $message->content[0]->text,
            ],
            200
        );
    }

}
