FROM 1x.ax/mamluk/php/8.4:franken-dev

# Copy files
COPY . /var/www/
COPY etc/caddy/Caddyfile /etc/frankenphp/
# COPY etc/caddy/caddy.d/* /etc/frankenphp/Caddyfile.d/

# Run Composer
RUN export COMPOSER_ALLOW_SUPERUSER=1 && cd /var/www && composer install --no-dev

# Delete stuff we do not need
RUN rm -rf /var/www/.git
RUN rm -rf /var/www/.gitignore

# Set the correct permissions
# RUN chown -R www-data:www-data /var/www/