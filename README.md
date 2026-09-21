<img src=".mamluk/logo.png" width="96" alt="Kipchak" />

# Kipchak Starter

This is the starter project for [Kipchak](https://kipchak.dev) — a PHP 8.4+ API Development Kit (ADK) built on top of [Slim Framework v4](https://www.slimframework.com/) and optimised for [FrankenPHP](https://frankenphp.dev/) in worker mode. It runs on PHP 8.4 and 8.5.

> Upgrading an existing project? See the [upgrade guide](https://kipchak.dev/docs/upgrades) for the changes in each major version.

The starter demonstrates all bundled drivers and middlewares, lays out an opinionated project structure, and is fully usable as a vanilla Slim application. Nothing in Slim is restricted.

**For complete documentation, visit <https://kipchak.dev>.**

---

## Install

```bash
composer create-project kipchak/starter [api-name] -s dev
```

Replace `[api-name]` with the directory name for your new project.

---

## Running with Docker

The starter ships with a `docker-compose.yml` that brings up the full stack:

```bash
docker compose up
```

This starts the following services:

| Service    | Description                           | Port(s)            |
|------------|---------------------------------------|--------------------|
| `api`      | FrankenPHP + Kipchak                  | `80`, `2019`       |
| `mysql`    | MySQL 8                               | internal           |
| `couchdb`  | Apache CouchDB 3.5                    | `5984`             |
| `memcached`| Memcached 1.6                         | internal           |
| `rabbitmq` | RabbitMQ 4 with management UI         | `5672`, `15672`    |

Hot-reload is enabled via Docker Compose `watch` — changes to your code restart the worker automatically.

### CouchDB first-run setup

CouchDB requires a `_users` database on first start. Run once after `docker compose up`:

```bash
curl -X PUT http://api:api@localhost:5984/_users
curl -X PUT http://api:api@localhost:5984/api
```

---

## Project Structure

```
├── api/
│   ├── Controllers/        # HTTP request handlers, versioned under v1/, v2/, etc.
│   ├── DataTransferObjects/ # Input/output contracts, versioned alongside controllers
│   ├── Entities/           # Database schema representations (Doctrine ORM, CouchDB)
│   ├── Middlewares/        # Custom per-project middleware
│   └── Models/             # Business logic
├── config/                 # PHP config files (one per driver/middleware)
├── drivers/
│   └── drivers.php         # Driver initialisation order
├── middlewares/
│   └── middlewares.php     # Global middleware registration
├── routes/                 # Route definitions, versioned under v1/, v2/, etc.
└── html/
    ├── index.php           # FrankenPHP worker-mode entry point
    └── index.nonworker.php # Standard PHP entry point
```

---

## Philosophy

Kipchak has an opinionated view of how API layers should be structured. These definitions follow the natural flow of a request.

- **Routes** — Entry points into your API. Defined in `routes/`, versioned by directory (e.g. `routes/v1/`). They declare the URL, HTTP verb, and which controller handles the request.

- **Middlewares** — Reusable logic applied before a request reaches a controller or before a response is sent. Global middlewares are registered in `middlewares/middlewares.php`. Route-level middlewares are applied in the route file itself.

- **Controllers** — Decide how to process a request. Versioned alongside routes. A controller receives the HTTP request, passes it to a Data Transfer Object for validation, then hands off to a Model.

- **Data Transfer Objects (DTOs)** — Represent data flowing in and out of your API. DTOs must be versioned — they form the API contract. Validating input here keeps controllers thin.

- **Models** — Business logic. The layer between your controllers and your data stores or third-party services.

- **Entities** — Code representations of your data schema. Doctrine entities live in `api/Entities/Doctrine/`. CouchDB document shapes live in `api/Entities/CouchDB/`.

- **Dependencies** — Common classes registered in the DI container so they are instantiated once per request. Drivers are Kipchak's built-in dependency layer, initialised in `drivers/drivers.php`.

---

## Drivers

Drivers are adapters for external services. They are initialised in `drivers/drivers.php` and registered into the DI container. Config, Logger, and HTTP are initialised first as other drivers depend on them.

The starter includes and demonstrates the following drivers:

### Core

| Driver       | Config file          | Description                                      |
|--------------|----------------------|--------------------------------------------------|
| Config       | —                    | Loads all PHP config files from `config/`. Always initialised first. |
| Logger       | `kipchak.api.php`    | PSR-3 logger (Monolog). Available as `$this->logger` in all controllers. |
| HTTP         | —                    | PSR-18 HTTP client for outbound requests. Used internally by several drivers. |

### Data & Messaging

| Driver       | Config file              | Demo route                   | Description                             |
|--------------|--------------------------|------------------------------|-----------------------------------------|
| Memcached    | `kipchak.memcached.php`  | `GET /v1/cache`              | Symfony Cache-backed Memcached pools    |
| File Cache   | —                        | `GET /v1/cache`              | Symfony Cache file-based cache          |
| CouchDB      | `kipchak.couchdb.php`    | `GET /v1/couch`              | Apache CouchDB 3.x document store       |
| Doctrine     | `kipchak.doctrine.php`   | —                            | Doctrine DBAL and ORM for MySQL/MariaDB |
| RabbitMQ     | `kipchak.rabbitmq.php`   | `GET /v1/rabbitmq/publish`   | RabbitMQ publisher and subscriber       |
|              |                          | `GET /v1/rabbitmq/subscribe` |                                         |
| S3           | `kipchak.s3.php`         | `GET /v1/s3`                 | S3-compatible object storage            |
| Git          | `kipchak.git.php`        | `GET /v1/git`                | Git repository operations over HTTPS    |

### AI

| Driver       | Config file              | Demo route          | Description                                  |
|--------------|--------------------------|---------------------|----------------------------------------------|
| OpenAI       | `kipchak.openai.php`     | `GET /v1/openai`    | OpenAI chat completions with default model injection |
| Anthropic    | `kipchak.anthropic.php`  | `GET /v1/anthropic` | Anthropic Claude messages with default model injection |

### DNS

| Driver          | Config file                  | Demo route                   | Description                         |
|-----------------|------------------------------|------------------------------|-------------------------------------|
| Cloudflare DNS  | `kipchak.cloudflare-dns.php` | `GET /v1/cloudflare-dns`     | Manage Cloudflare zones and records |
| ClouDNS         | `kipchak.cloudns.php`        | `GET /v1/cloudns`            | Manage ClouDNS zones and records    |

---

## Middlewares

Middlewares are registered in `middlewares/middlewares.php` and apply globally to every request.

| Middleware    | Config file            | Description                                                                 |
|---------------|------------------------|-----------------------------------------------------------------------------|
| Error         | —                      | Standardised JSON error responses for all unhandled exceptions              |
| API Key       | `kipchak.auth.key.php` | Validates an API key from a header or query parameter. Disabled by default. |
| JWKS          | `kipchak.auth.jwks.php`| Validates a Bearer JWT against a remote JWKS URI. Disabled by default.      |
| Subashi (WAF) | `kipchak.subashi.php`  | Request firewall with OWASP rules, rate limiting, IP/header allow/blocklists. Enabled by default. |

Authentication middlewares are disabled by default (`'enabled' => false`) so the demo routes are accessible without credentials. Set `'enabled' => true` in the relevant config file to activate.

The starter also includes a demo of per-route middleware usage:

| Route                | Middleware applied     |
|----------------------|------------------------|
| `GET /v1/authKey`    | API Key authentication |
| `GET /v1/authJWKS`  | JWKS authentication    |

Custom per-project middlewares can be added to `api/Middlewares/` and registered in `middlewares/middlewares.php` or applied directly in a route file.

---

## Configuration

All configuration lives in the `config/` directory as PHP files returning arrays. The Config driver loads every file in this directory at boot and makes them available via `Config::get('filename-without-extension')`.

Environment variables are read using the `env()` helper:

```php
env('ENV_VAR_NAME', 'default-value')
```

| File                          | Purpose                                                   |
|-------------------------------|-----------------------------------------------------------|
| `kipchak.api.php`             | App name, debug mode, log level                           |
| `kipchak.auth.key.php`        | API key authentication settings                           |
| `kipchak.auth.jwks.php`       | JWKS URI and scope validation settings                    |
| `kipchak.anthropic.php`       | Anthropic API key and default model                       |
| `kipchak.cloudflare-dns.php`  | Cloudflare API token(s)                                   |
| `kipchak.cloudns.php`         | ClouDNS credentials                                       |
| `kipchak.couchdb.php`         | CouchDB host, port, credentials, and database             |
| `kipchak.doctrine.php`        | Doctrine DBAL connections and ORM entity manager settings |
| `kipchak.git.php`             | Git clone directory and named repository definitions      |
| `kipchak.memcached.php`       | Memcached server pools and options                        |
| `kipchak.openai.php`          | OpenAI API key, organisation, project, and default model  |
| `kipchak.rabbitmq.php`        | RabbitMQ connection and queue definitions                 |
| `kipchak.s3.php`              | S3-compatible endpoint(s), credentials, and region        |
| `kipchak.subashi.php`         | WAF rules: whitelist, blacklist, and rate limit rules     |

---

## Writing Custom Controllers

Controllers extend `Kipchak\Core\Components\Controllers\Base`, which provides:

- `$this->container` — the DI container
- `$this->logger` — the PSR-3 logger

```php
namespace Api\Controllers\v1;

use Kipchak\Core\Components\Controllers;
use Kipchak\Core\Components\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Example extends Controllers\Base
{
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return Http\Response::json($response, ['hello' => 'world'], 200);
    }
}
```

## Writing Custom Middlewares

Custom middlewares implement `__invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)` and can be applied globally in `middlewares/middlewares.php` or to a specific route group.

```php
$app->group('/v1', function (RouteCollectorProxy $group) {
    $group->get('/protected', [Controllers\v1\Example::class, 'get'])
          ->add(new MyCustomMiddleware());
});
```

## Registering Custom Dependencies

Add any class or service to the DI container in `drivers/drivers.php`:

```php
$container->set('my.service', function (ContainerInterface $c) {
    return new MyService();
});
```

Retrieve it anywhere you have access to the container:

```php
$service = $this->container->get('my.service');
```
