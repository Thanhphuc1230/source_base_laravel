#!/bin/sh
set -e

# Dynamic port binding for Cloud PaaS (Railway / Render / Fly.io)
PORT=${PORT:-80}
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/conf.d/default.conf
sed -i "s/listen \[::\]:80;/listen \[::\]:${PORT};/g" /etc/nginx/conf.d/default.conf

echo "Starting Laravel initialization on port ${PORT}..."

# Cache configuration & routes
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional auto-migration
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

echo "Launching Supervisor (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
