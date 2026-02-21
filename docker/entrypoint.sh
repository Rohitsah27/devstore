#!/usr/bin/env bash
set -e

# Use the PORT environment variable in Apache configuration
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
fi

# Run database migrations (optional, uncomment if needed)
# php artisan migrate --force

# Cache configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Execute the main container command
exec "$@"
