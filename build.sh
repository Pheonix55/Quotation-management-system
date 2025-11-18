#!/bin/bash
set -e  # Exit immediately if any command fails

echo "=== Installing Composer dependencies ==="
composer install --no-dev --optimize-autoloader

echo "=== Generating APP_KEY ==="
php artisan key:generate

echo "=== Caching config and routes ==="
php artisan config:cache
php artisan route:cache

echo "=== Creating storage symlink ==="
php artisan storage:link

echo "=== Installing NPM dependencies ==="
npm ci --legacy-peer-deps

echo "=== Building frontend assets ==="
npm run build
