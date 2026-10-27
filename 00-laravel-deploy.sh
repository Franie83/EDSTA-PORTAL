#!/bin/sh
set -eu

cd /var/www/html

echo "Caching Laravel configuration..."
php artisan config:cache

echo "Caching Laravel routes..."
php artisan route:cache

echo "Running database migrations..."
php artisan migrate --force

echo "Creating Laravel storage link..."
php artisan storage:link --force

echo "Laravel deployment preparation completed."
