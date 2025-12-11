#!/bin/bash

echo $SERVER_ROOT

# Generate Doctrine ORM proxies
cd /var/www && vendor/bin/doctrine orm:generate-proxies --em primary

# Start FrankenPHP / Caddy
cd $SERVER_ROOT
exec frankenphp run --config /etc/frankenphp/Caddyfile