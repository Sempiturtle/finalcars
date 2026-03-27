#!/usr/bin/env bash
set -e

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
