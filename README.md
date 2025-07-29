[version-badge]: https://poser.pugx.org/mamluk/kipchak-starter/v
[downloads-badge]: https://poser.pugx.org/mamluk/kipchak-starter/downloads
[unstable-badge]: https://poser.pugx.org/mamluk/kipchak-starter/v/unstable
[license-badge]: https://poser.pugx.org/mamluk/kipchak-starter/license
[php-badge]: https://poser.pugx.org/mamluk/kipchak-starter/require/php
[packagist-url]: https://packagist.org/packages/mamluk/kipchak-starter
[slim-url]: https://www.slimframework.com/

![Version][version-badge]
![Downloads][downloads-badge]
![Unstable][unstable-badge]
![License][license-badge]
![PHP Version][php-badge]

<img src="https://1x.ax/mamluk/kipchak/core/~raw/master/.mamluk/logo.svg" />

# Kipchak Starter Project

This is a starter project based on the Kipchak (https://1.ax/mamluk/kipchak/core) API Development Kit (ADK) - 
which is a set of components added on top of Slim Framework (https://www.slimframework.com/) to 
help build APIs rapidly. 

This starter demonstrates all the features of Kipchak which are configurable largely via YAML files in 
the config folder, and it lays out a foundation for an opinionated way of using Slim. It does not restrict
any anything within Slim, and you can still use it as a vanilla Slim application.

## Install and Get Started
Run this command from the directory to create a starter Kipchak API from the starter.

```
composer create-project mamluk/kipchak-starter [api-name] -s dev
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

* Dependencies - Dependencies are common libraries or classes that you might want to use throughout your API.
You inject them as dependencies into the service container so they are created only once during the request / response
lifecycle, saving memory, cpu and valuable milliseconds. Explaining what a service / IO / dependency container is not part 
of the scope of this documentation, but if you don't know what it is,
<a href="http://fabien.potencier.org/do-you-need-a-dependency-injection-container.html" target="_blank">this article</a> can help.

## Kipchak provided database and cache clients
The **Community Edition** of Kipchak supports the following:

* **Memcached** (based on Symfony Cache)
* **Apache CouchDB** (3.2+)
* **MySQL** or a MySQL drop-in (Percona, MariaDB, etc.) (based on Doctrine)

Additional libraries and clients are available as part of *Enterprise Support*. These include, amongst others:

* **Valkey** support for caching
* **JWT** authentication / authorisation with a public key
* **Binary only or security verified** containers
* **RBAC Management**
* **Personal Access Token Management**
* **Rate limting** by IP address / user / API key
* **OAuth 2 Client** with simplified token management for upstream APIs

To learn more about Kipchak Enterprise Edition, please reach out via https://mamluk.net/contact.

## Kipchak Provided Dependencies

Kipchak ADK provides several built-in dependencies that are automatically configured and ready to use in your application:

### Database Clients
- **CouchDB Client** - Preconfigured Apache CouchDB client for document storage
- **MySQL/MariaDB Client** - Doctrine-based RDBMS client with ORM support
- **Memcached Client** - Symfony Cache-based Memcached client for caching

### Authentication & Session Management
- **Session Handlers**
  - CouchDB Session Storage
  - Memcached Session Storage
- **Authentication Providers** (Community Edition)
  - API Key Authentication
  - OAuth JWKS Verification

### HTTP Components
- **HTTP Client** - Pre-configured HTTP client for making external API requests
- **CORS Configuration** - Built-in CORS support

All these dependencies are automatically wired into the service container and can be configured through the respective configuration files in the `config/` directory:
- `kipchak.doctrine.php` - MySQL/Database settings
- `kipchak.couchdb.php` - CouchDB connection configuration
- `kipchak.memcached.php` - Memcached settings
- `kipchak.sessions.php` - Session handling configuration
- `kipchak.auth.php` - Authentication settings

## Kipchak Provided Middlewares

Kipchak ADK comes with several pre-configured middleware components that help handle common API requirements:

### Authentication Middlewares
- **API Key Authentication** - Validates requests using API keys
- **OAuth JWKS Verification** - Handles JWT token validation using JWKS
- Configurable through `kipchak.auth.php`

### Security Middlewares
- **Session Management** - Handles session operations for Backend for Frontend (BFF) applications
  - Supports CouchDB or Memcached as session storage backends
  - Configurable through `kipchak.sessions.php`

### Response Middlewares
- **Error Handler** - Provides standardized error responses

Most middlewares can be:
- Configured through the respective configuration files in the `config/` directory
- Applied globally or to specific route groups
- Customized through their configuration parameters

Global middleware configuration can be managed in the `middlewares` directory of your project.

## Injecting your own dependencies
Please see `sample.php` in the dependencies folder. Dependencies can be used via `$container->get()` as in a default Slim implementation.

## Writing your own Middlewares
Please see `sample.php` in the middlewares folder. Once added, a middleware can be applied globally or a route as in a default Slim implementation.

### To Do
* Add tests
* Add sample helm / k8s manifest

### Running CouchDB Locally for Development
Once the CouchDB Container comes up, you might see some errors in the log. This is because
CouchDB expects a _users database to be created for user management. Let's create this 
so CouchDB is ready to be used. Run the following on your terminal:

```
curl -X PUT http://api:api@localhost:5984/_users
curl -X PUT http://api:api@localhost:5984/api
```

You can also create a new database(s) for managing any data your API creates. Let's create a database called 'api':

```
curl -X PUT http://api:api@localhost:5984/api
```

### Session Handling
If you are  building  a Backend for Frontend (BfF) with Kipchak, you will need to enable session handling.
Kipchak supports sessions using either CouchDB or Memcached. This can be configured in config/kipchak.sessions.php.

#### With CouchDB

If you are enabling session management with CouchDB in the kipchak.sessions.php config file in the config folder, you
should also create database to manage these sessions set this correctly on line 13 of this file.

If this is set to `api_sessions`, for instance, run the following in your terminal to create the database:

```
curl -X PUT http://api:api@localhost:5984/api_sessions
```