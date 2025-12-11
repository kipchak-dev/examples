#!/bin/bash

# Generate Doctrine ORM proxies
cd /var/www && vendor/bin/doctrine orm:generate-proxies

# Start FrankenPHP / Caddy
exec frankenphp run --config /etc/frankenphp/Caddyfile
