FROM serversideup/php:8.3-fpm-nginx

# Force Render to use port 10000
ENV PORT=10000


# Install PostgreSQL extension for PDO

# Copy application code
COPY . /var/www/html

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
CMD ["/etc/entrypoint.d/50-laravel-automations.sh"]
