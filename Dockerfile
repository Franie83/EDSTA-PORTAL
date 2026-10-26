FROM serversideup/php:8.3-fpm-nginx

# Set ports for Render and NGINX
ENV PORT=10000
ENV NGINX_PORT=10000

# Install required PHP extensions
# Switch to root to install extensions
USER root
RUN install-php-extensions gd

# Drop back to unprivileged user

# Force Render to use port 10000

# Install PostgreSQL extension for PDO

# Copy application code
COPY . /var/www/html

# Create necessary directories and set permissions BEFORE composer install
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Set working directory
WORKDIR /var/www/html

# Image config (Required for Laravel on Render)
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV COMPOSER_ALLOW_SUPERUSER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Run the deploy script on startup

# Switch back to unprivileged user for runtime
# Setup manual startup script

USER www-data

COPY clear-cache.sh /usr/local/bin/clear-cache.sh
RUN chmod +x /usr/local/bin/clear-cache.sh
ENTRYPOINT ["/usr/local/bin/clear-cache.sh"]

# Ensure no stale caches from previous builds
RUN rm -f bootstrap/cache/*.php

