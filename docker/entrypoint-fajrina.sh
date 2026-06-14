#!/bin/bash
# entrypoint-fajrina.sh

if [ -z "$(grep '^APP_KEY=base64' /var/www/html/.env)" ]; then
    php artisan key:generate
fi

echo "Menunggu database siap..."
sleep 10
php artisan migrate --force

php-fpm -D
nginx -g "daemon off;"
