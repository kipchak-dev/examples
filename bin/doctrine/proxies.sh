#!/bin/bash

# Generate Doctrine ORM proxies
cd /var/www && vendor/bin/doctrine orm:generate-proxies

# Start
exec frankenphp run --config /etc/caddy/Caddyfile
