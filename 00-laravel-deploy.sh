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
if [ -L public/storage ]; then
    echo "Storage symlink already exists; skipping creation."
elif [ -e public/storage ]; then
    echo "ERROR: public/storage exists but is not a symlink. Refusing to overwrite it."
    exit 1
else
    php artisan storage:link
fi

echo "Laravel deployment preparation completed."
