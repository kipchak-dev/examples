[version-badge]: https://poser.pugx.org/mamluk/kipchak-template/v
[downloads-badge]: https://poser.pugx.org/mamluk/kipchak-template/downloads
[unstable-badge]: https://poser.pugx.org/mamluk/kipchak-template/v/unstable
[license-badge]: https://poser.pugx.org/mamluk/kipchak-template/license
[php-badge]: https://poser.pugx.org/mamluk/kipchak-template/require/php
[packagist-url]: https://packagist.org/packages/mamluk/kipchak-template
[slim-url]: https://www.slimframework.com/

![Version][version-badge]
![Downloads][downloads-badge]
![Unstable][unstable-badge]
![License][license-badge]
![PHP Version][php-badge]

<img src="https://1x.ax/mamluk/kipchak/core/~raw/master/.mamluk/logo.svg" alt="Kipchak by Mamluk Starter Project" title="Kipchak by Mamluk Starter Project" />

# Kipchak Starter Project

This is a starter project based on Kipchak Core 2.x (https://1.ax/mamluk/kipchak/core/~files/2.x) - an API Development Kit (ADK) built on top of Slim Framework (https://www.slimframework.com/) designed for rapid API development.

Kipchak 2.x introduces a modular architecture with drivers and middleware as separate, composable packages, making it lightweight and extensible. This starter demonstrates all the features of Kipchak, which are configurable via PHP files in the config folder. It provides an opinionated structure for building APIs while maintaining full compatibility with vanilla Slim Framework.

## Install and Get Started
Run this command from the directory to create a starter Kipchak API from the starter.

```
composer create-project kipchak/starter [api-name] -s dev
```

Replace ```[api-name]``` with the desired directory name for your new application.

## Some Philosophy for the Philosophware Engineers
The Kipchak starter is structured with some assumptions about how APIs should be developed. These are
based around understanding definitions of certain common concepts like Entities, Models, etc. Our definitions
are spelled out here. We'll deal with these in the order in which a request flows through Kipchak.

* Routes - These are the first port of entry into your api. Specified in the ```routes``` directory, 
these should be versioned and generally call a controller, with or without invoking some middleware. They 
determine the URL and HTTP verb of an endpoint in your API.

* Middlewares - Middleware is a general, re-usable functionality you want to apply to one or more routes 
before they reach the controller or just before a response is served. Global middlewares can be configured 
in the ```middlewares``` directory.

* Controllers - These are where you decide how to process an HTTP request. These should also be 
versioned alongside the routes so breaking changes to your API contracts can be managed consistently. Controllers
will usually receive an HTTP request, pass it to a Data Transfer Object to ensure that it meets the requirements
for the request, then pass the DTO to the model, which may invoke some business logic and entities before passing the response
back to the client. Controllers will also contain PHP attributes which formulate part of your OpenAPI specification.
* Models - This is where you write your business logic - what happens between the database or a third party API and 
your API.

* Entities - These are representations of your database schema in code. If you use Doctrine as the ORM for an RBDMS, you
will specify Doctrine entities here. If you are using Couchbase, you would have an object representation of what 
will get stored in a CouchDB document.

* Data Transfer Objects - These are effectively what represent the data transferred to and from your API to
HTTP clients or any other external systems. These must be versioned, and are the first step to ensuring that any data that comes with the 
request matches what you expect. These must also be versioned, as they are ultimately what form the API contract.

* Drivers - Drivers are common libraries or classes that you might want to use throughout your API.
You inject them as dependencies into the service container so they are created only once during the request / response
lifecycle, saving memory, cpu and valuable milliseconds. Explaining what a service / IO / dependency container is not part 
of the scope of this documentation, but if you don't know what it is,
<a href="http://fabien.potencier.org/do-you-need-a-dependency-injection-container.html" target="_blank">this article</a> can help.

## Requirements

* PHP 8.4+
* Composer

## Run the API and see it in action

This starter project comes with a docker-compose file that will start the API, CouchDB, Memcached and MySQL containers for a full experience.

Kipchak runs in worker mode using FrankenPHP for extremely high and efficient performance.

Simply run ```docker compose up --build --watch``` from the root of the project and develop like you normally would.

If you don't want to use any of the drivers or middlewares, simply remove them from the composer.json file and run `composer update`. You can also remove the related config file in the config directory.

## Kipchak 2.x Architecture

Kipchak 2.x has been redesigned with a modular architecture where drivers and middleware are separate packages. This allows you to install only what you need, keeping your application lightweight.

### What's New in 2.x

* **Modular Package Structure**: Drivers and middleware are now standalone packages
* **New Namespace**: Changed from `Mamluk\Kipchak` to `Kipchak`
* **Driver Interface**: Standardized interface for creating custom drivers
* **Middleware Interface**: Standardized interface for creating custom middleware
* **Lightweight Core**: Core package contains only essential components

## Available Drivers (Community Edition)

Kipchak 2.x provides the following drivers as separate packages:

* **kipchak/driver-config** - Configuration management
* **kipchak/driver-logger** - Logging (Monolog-based)
* **kipchak/driver-http** - HTTP client for external API calls
* **kipchak/driver-filecache** - File-based caching (Symfony Cache)
* **kipchak/driver-memcached** - Memcached support (Symfony Cache)
* **kipchak/driver-couchdb** - Apache CouchDB client (3.2+)
* **kipchak/driver-doctrine** - Doctrine ORM for MySQL/MariaDB/Percona
* **kipchak/driver-rabbitmq** - RabbitMQ messaging
* **kipchak/driver-object-storage** - Object storage integration

## Available Middleware (Community Edition)

* **kipchak/middleware-error** - Error handling and standardized error responses
* **kipchak/middleware-auth** - Authentication (API Key, JWKS OAuth verification)
* **kipchak/middleware-subash** - A WAF for API endpoints

## Enterprise Edition Features

Additional drivers and middleware are available as part of *Enterprise Support*:

* **Valkey** support for caching
* **JWT** authentication / authorisation with a public key
* **Binary only or security verified** containers
* **RBAC Management**
* **Personal Access Token Management**
* **OAuth 2 Client** with simplified token management for upstream APIs

To learn more about Kipchak Enterprise Edition, please reach out via https://mamluk.net/contact.

## Working with Drivers

### Using Drivers in Your Application

Drivers must be initialized in `drivers/drivers.php` before they can be used. The order matters - drivers like Config, Logger, and HTTP should be initialized first as they may be used by other drivers:

```php
<?php
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */

// The initialise method also has a second parameter to pass a custom config file name if the driver gives you an option to do so.
\Kipchak\Driver\Config\Driver::initialise($container);
\Kipchak\Driver\Logger\Driver::initialise($container);
\Kipchak\Driver\Http\Driver::initialise($container);
\Kipchak\Driver\Filecache\Driver::initialise($container);
\Kipchak\Driver\Memcached\Driver::initialise($container);
\Kipchak\Driver\CouchDB\Driver::initialise($container);
\Kipchak\Driver\Doctrine\Driver::initialise($container);
```

### Accessing Drivers in Controllers

Once initialized, drivers can be accessed using their static `get()` method:

```php
<?php
namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Kipchak\Driver\CouchDB\Driver as CouchDB;
use Kipchak\Driver\Memcached\Driver as MemcachedDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Example extends Controllers\Base
{
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Access CouchDB driver with connection name
        $couchdb = CouchDB::get('default');

        // Access Memcached driver with cache pool name
        $cache = MemcachedDriver::get('cache');

        // Use the drivers
        $couchdb->createDatabase();
        $document = json_encode(['key' => 'value']);
        $couchdb->create('doc-id', $document);

        // Cache with expiration
        $value = $cache->get('cache-key', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return 'cached value';
        });

        return Http\Response::json($response, ['status' => 'success'], 200);
    }
}
```

### Creating Custom Drivers

To create a custom driver, implement the `DriverInterface` from Kipchak Core:

```php
<?php
namespace YourApp\Drivers;

use Kipchak\Core\Interfaces\Driver;
use Psr\Container\ContainerInterface;

class Driver implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container, ?string $configFileName = null): void
    {
        self::$container = $container;
        self::set($configFileName);
    }

    public static function set(?string $configFileName = null): void
    {
        $configFileName = $configFileName ?? 'kipchak.myconfignamefordriver';
        // Add some logic
        // Set driver in the container
        self::$container->set('drivers.mydriver', function (ContainerInterface $c) use ($configFileName): SomeDriver {
            // Do whatever needs doing and return the driver instance
            
            return SomeDriver;
        });
            
    }

    public static function get(?string $configKey = null): Client
    {
        // Use $configKey if your config file allows for multiple instances, for example a connection to multiple s3 buckets for doctrine entity managers
        return self::$container->get('drivers.mydriver.' . $configKey ?? 'default');
    }

}

```

Then initialize it in `drivers/drivers.php`:

```php
\YourApp\Drivers\Driver::initialise($container);
```

## Working with Middleware

### Using Middleware

Middleware can be applied globally in `middlewares/middlewares.php`:

```php
<?php
use Slim\App;

/** @var App $app */

// Initialize global middleware
\Kipchak\Middleware\Error\Middleware::initialise($app);
\Kipchak\Middleware\Auth\Middleware::initialise($app);
```

Or apply to specific routes:

```php
<?php
$app->get('/protected', [Controller::class, 'method'])
    ->add(\Kipchak\Middleware\Auth\Middleware::get());
```

### Creating Custom Middleware

To create custom middleware, implement the `MiddlewareInterface` from Kipchak Core:

```php
<?php
namespace YourApp\Middlewares;

use Kipchak\Core\Interfaces\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;

class CustomMiddleware implements Middleware
{
    protected static ?self $instance = null;
    protected App $app;

    /**
     * Initialize middleware with the Slim app
     */
    public static function initialise(App $app): void
    {
        if (self::$instance === null) {
            self::$instance = new self($app);
            $app->add(self::$instance);
        }
    }

    /**
     * Get middleware instance for route-specific application
     */
    public static function get(): self
    {
        if (self::$instance === null) {
            throw new \RuntimeException('Middleware not initialized');
        }
        return self::$instance;
    }

    private function __construct(App $app)
    {
        $this->app = $app;

        // Access container for config, logger, etc.
        $container = $app->getContainer();
        $this->config = $container->get('config')->get('your-middleware') ?? [];
    }

    /**
     * Process the request
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        // Before logic - modify request or check conditions
        $customHeader = $request->getHeader('X-Custom-Header');

        if (empty($customHeader)) {
            // Return early response if needed
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Missing header']));
            return $response->withStatus(400)
                           ->withHeader('Content-Type', 'application/json');
        }

        // Continue to next middleware/controller
        $response = $handler->handle($request);

        // After logic - modify response
        return $response->withHeader('X-Processed-By', 'CustomMiddleware');
    }
}
```

Then add it to `middlewares/middlewares.php`:

```php
\YourApp\Middlewares\CustomMiddleware::initialise($app);
```

## Configuration

All drivers and middleware are configured through PHP files in the `config/` directory:

- `kipchak.api.php` - API settings (name, debug mode, logging). **This file is mandatory.**
- `kipchak.doctrine.php` - MySQL/Database settings. Default location for kipchak/driver-doctrine.
- `kipchak.couchdb.php` - CouchDB connection configuration. Default location for kipchak/driver-couchdb.
- `kipchak.memcached.php` - Memcached settings. Default location for kipchak/driver-memcached.
- `kipchak.auth.php` - Authentication settings. Default location for kipchak/middleware-auth.
- `kipchak.subash.php` - WAF settings. Default location for kipchak/middleware-subash.

See the documentation for each driver or middleware for more configuration options.

Configuration files return arrays that can be accessed via the Config driver.

### To Do
* Add tests
* Add sample helm / k8s manifest

## Running CouchDB Locally for Development

Once the CouchDB container starts, you may see errors in the log because CouchDB expects a `_users` database for user management. Create it by running:

```bash
curl -X PUT http://api:api@localhost:5984/_users
curl -X PUT http://api:api@localhost:5984/api
```

Create additional databases for your API data as needed:

```bash
curl -X PUT http://api:api@localhost:5984/api
```

## Additional Resources

* [Kipchak Core Repository](https://1.ax/mamluk/kipchak/core/~files/2.x)
* [Slim Framework Documentation](https://www.slimframework.com/)
* [Dependency Injection Containers](http://fabien.potencier.org/do-you-need-a-dependency-injection-container.html)

## Support

For questions, issues, or enterprise support inquiries, visit [Mamluk Contact](https://mamluk.net/contact).