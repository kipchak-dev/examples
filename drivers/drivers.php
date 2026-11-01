<?php

use Kipchak\Driver\Anthropic\Anthropic;
use Kipchak\Driver\CloudflareDns\CloudflareDns;
use Kipchak\Driver\ClouDns\ClouDns;
use Kipchak\Driver\Config\Config;
use Kipchak\Driver\ClickHouse\ClickHouse;
use Kipchak\Driver\CouchDB\CouchDB;
use Kipchak\Driver\Doctrine\Doctrine;
use Kipchak\Driver\FeatureFlags\FeatureFlags;
use Kipchak\Driver\Filecache\Filecache;
use Kipchak\Driver\Git\Git;
use Kipchak\Driver\Http\Http;
use Kipchak\Driver\Logger\Logger;
use Kipchak\Driver\NATS\NATS;
use \Kipchak\Driver\Memcached\Memcached;
use Kipchak\Driver\OpenAI\OpenAI;
use Kipchak\Driver\RabbitMQ\RabbitMQ;
use Kipchak\Driver\S3\S3;
use Kipchak\Driver\Sftp\Sftp;
use Kipchak\Driver\Vector\Vector;
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
Git::initialise($container);
S3::initialise($container);
Sftp::initialise($container);
FeatureFlags::initialise($container);
OpenAI::initialise($container);
Anthropic::initialise($container);
CloudflareDns::initialise($container);
ClouDns::initialise($container);
ClickHouse::initialise($container);
Vector::initialise($container);
NATS::initialise($container);
