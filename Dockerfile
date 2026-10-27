FROM serversideup/php:8.3-fpm-nginx

USER root

# Required PHP extensions for image processing and PostgreSQL.
RUN install-php-extensions gd pdo_pgsql

WORKDIR /var/www/html

# Copy application source.
COPY . /var/www/html

# Prepare Laravel writable directories.
RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install locked production dependencies.
RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
    && rm -f bootstrap/cache/*.php

# Register the Laravel deployment script with ServerSideUp.
COPY --chmod=755 ./00-laravel-deploy.sh /etc/entrypoint.d/99-laravel-deploy.sh

# Render and Laravel production configuration.
ENV PORT=10000
ENV NGINX_PORT=10000
ENV WEBROOT=/var/www/html/public
ENV SKIP_COMPOSER=1
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Run the application as an unprivileged user.
USER www-data
