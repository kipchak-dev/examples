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
// Error is initialised first and last. The first turns an exception from a route into a JSON error response that
// the middleware below can observe; the last, the outermost middleware, does the same for an exception thrown by
// any of them.
Error::initialise($app);
// Slim runs the last-added middleware first, so these run after authentication, and the feature flag gates in
// config run before the response cache.
ResponseCache::initialise($app);
FeatureFlags::initialise($app);
Key::initialise($app);
JWKS::initialise($app);
Subashi::initialise($app);
Error::initialise($app);

