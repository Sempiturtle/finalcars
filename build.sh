#!/usr/bin/env bash
# exit on error
set -e

echo "Running composer..."
composer install --no-dev

echo "Running npm..."
npm install
npm run build

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear

echo "Deployment preparation complete!"
