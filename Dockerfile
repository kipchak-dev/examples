FROM 1x.ax/mamluk/php/8.4:franken

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

# The correct environment variables are set in the docker-compose file. Set any other ones here.

###############################################################################################################
## If you are using Doctrine ORM, comment out the next 3 lines to generate proxies at container startup time. ##
# COPY bin/doctrine/proxies.sh /usr/local/bin/doctrine-proxies.sh
# RUN chmod -R 777 /tmp && chmod 755 /usr/local/bin/doctrine-proxies.sh
# CMD ["/usr/local/bin/doctrine-proxies.sh"]
###################################### Doctrine Proxies end ###################################################