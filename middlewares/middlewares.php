<?php
// A Middleware added here will apply globally to every request.
use Kipchak\Middleware\Subashi\Subashi;
use Kipchak\Middleware\Error\Error;
use Kipchak\Middleware\Auth\Key\Key;
use Kipchak\Middleware\Auth\JWKS\JWKS;
use Slim\App;

/**
 * @var App $app
 */
Error::initialise($app);
Key::initialise($app);
JWKS::initialise($app);
Subashi::initialise($app);

