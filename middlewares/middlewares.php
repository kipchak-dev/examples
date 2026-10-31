<?php
// A Middleware added here will apply globally to every request.
use Kipchak\Middleware\Subashi\Subashi;
use Kipchak\Middleware\Error\Error;
use Kipchak\Middleware\Auth\Key\Key;
use Kipchak\Middleware\Auth\JWKS\JWKS;
use Kipchak\Middleware\ResponseCache\ResponseCache;
use Kipchak\Middleware\FeatureFlags\FeatureFlags;
use Slim\App;

/**
 * @var App $app
 */
Error::initialise($app);
// Slim runs the last-added middleware first, so these run after authentication, and the feature flag gates in
// config run before the response cache.
ResponseCache::initialise($app);
FeatureFlags::initialise($app);
Key::initialise($app);
JWKS::initialise($app);
Subashi::initialise($app);

