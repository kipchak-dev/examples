<?php
// A Middleware added here will apply globally to every request.
use Kipchak\Middleware\Error\Error;
use Kipchak\Middleware\Auth\Auth;
use Slim\App;

/**
 * @var App $app
 */
Error::initialise($app);
Auth::initialise($app);

