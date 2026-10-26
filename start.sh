#!/bin/bash
set -e

echo "🚀 Starting manual Laravel setup..."

# Wait for the database to be ready (max 60 seconds)
echo "⏳ Waiting for database..."
timeout 60 bash -c 'until php artisan db:show > /dev/null 2>&1; do sleep 2; done' || echo "⚠️ DB check timed out, proceeding anyway..."

# Run migrations and seeders
echo "📦 Running migrations..."
php artisan migrate --force || echo "⚠️ Migration failed or already ran."

echo "🌱 Seeding database (if empty)..."
php artisan db:seed --force || echo "⚠️ Seeding failed or already ran."

echo "✅ Manual setup complete. Starting web server..."

# Execute the original entrypoint to start Nginx/PHP-FPM
exec /init