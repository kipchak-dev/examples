FROM 1x.ax/mamluk/php/8.5:franken-dev

# Copy files
COPY . /var/www/

# Leave the below line as it is to run FrankenPHP in Worker Mode
COPY etc/caddy/Caddyfile /etc/frankenphp/

# If you do not want FrankenPHP to run in worker mode, uncomment the line below and comment line 7.
# COPY etc/caddy/Caddyfile.nonworker /etc/frankenphp/Caddyfile

# Run Composer
RUN export COMPOSER_ALLOW_SUPERUSER=1 && cd /var/www && composer install --no-dev

# Delete stuff we do not need
RUN rm -rf /var/www/.git
RUN rm -rf /var/www/.gitignore