#!/bin/sh
set -e

cd /var/www/html

mkdir -p database
[ -f database/database.sqlite ] || touch database/database.sqlite

chown -R www-data:www-data storage bootstrap/cache database || true
chmod -R 775 storage bootstrap/cache database || true

php artisan migrate --force
php artisan app:init

chown -R www-data:www-data /data || true

# Start cron daemon
cron

# Start Apache in foreground
exec "$@"