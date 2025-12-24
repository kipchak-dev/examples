<?php
use Psr\Container\ContainerInterface;
/** Initialise any drivers installed or coded in the project over here
 * Order is important. Config, Logger and HTTP are likely to be used by others, so are initialised first.
 * */

/**
 * @var ContainerInterface $container
 */

\Kipchak\Driver\Config\Driver::initialise($container);
\Kipchak\Driver\Logger\Driver::initialise($container);
\Kipchak\Driver\Http\Driver::initialise($container);
\Kipchak\Driver\Filecache\Driver::initialise($container);
\Kipchak\Driver\Memcached\Driver::initialise($container);
\Kipchak\Driver\CouchDB\Driver::initialise($container);
\Kipchak\Driver\Doctrine\Driver::initialise($container);
