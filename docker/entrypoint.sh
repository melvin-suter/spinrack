#!/bin/sh
set -e

cd /var/www/html

# Create DB if missing (useful when DB is a mounted volume)
mkdir -p database
[ -f database/database.sqlite ] || touch database/database.sqlite

# Ensure permissions (when using bind mounts on Linux this may not help much)
chown -R www-data:www-data storage bootstrap/cache database || true
chmod -R 775 storage bootstrap/cache database || true

# Run migrations
php artisan migrate --force
php artisan app:init-app

chown -R www-data:www-data /data

# Continue with Apache
exec "$@"

