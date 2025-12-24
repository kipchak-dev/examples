<?php
// A Middleware added here will apply globally to every request.
use Slim\App;
/**
 * @var App $app
 */

\Kipchak\Middleware\Error\Middleware::initialise($app);

