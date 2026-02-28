#!/bin/sh
set -e

PORT=${PORT:-8080}
sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/nginx.conf

cd /var/www/html

echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache

echo "==> Starting on port ${PORT}..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
