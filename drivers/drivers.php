<?php

use Kipchak\Driver\Config\Config;
use Kipchak\Driver\CouchDB\CouchDB;
use Kipchak\Driver\Doctrine\Doctrine;
use Kipchak\Driver\Filecache\Filecache;
use Kipchak\Driver\Http\Http;
use Kipchak\Driver\Logger\Logger;
use \Kipchak\Driver\Memcached\Memcached;
use Kipchak\Driver\RabbitMQ\RabbitMQ;
use Psr\Container\ContainerInterface;

/** Initialise any drivers installed or coded in the project over here
 * Order is important. Config, Logger and HTTP are likely to be used by others, so are initialised first.
 * */

/**
 * @var ContainerInterface $container
 */

Config::initialise($container);
Logger::initialise($container);
Http::initialise($container);
Filecache::initialise($container);
Memcached::initialise($container);
CouchDB::initialise($container);
Doctrine::initialise($container);
RabbitMQ::initialise($container);
