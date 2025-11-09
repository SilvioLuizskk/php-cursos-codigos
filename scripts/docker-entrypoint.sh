#!/usr/bin/env bash
set -euo pipefail

# Entry point for php-fpm container: wait for DB, install deps, migrate, then start php-fpm

APP_DIR=/var/www/html

cd "$APP_DIR"

echo "Waiting for DB to be ready..."
./scripts/wait-for.sh db:3306

if [ ! -f "$APP_DIR/vendor/autoload.php" ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --prefer-dist --no-progress
fi

echo "Running migrations"
php artisan migrate --force || true

echo "Starting php-fpm"
exec php-fpm
