#!/bin/bash
# entrypoint-fajrina.sh

# Generate APP_KEY kalau belum ada
if [ -z "$(grep '^APP_KEY=base64' /var/www/html/.env)" ]; then
    php artisan key:generate
fi

# Tunggu database siap (retry sampai max 60 detik)
echo "Menunggu koneksi database (db_fajrina:3306)..."
for i in $(seq 1 30); do
    php artisan migrate:status > /dev/null 2>&1 && break
    echo "DB belum siap, retry $i/30..."
    sleep 2
done

php artisan migrate --force

# Perbaiki ownership setelah artisan jalan (artisan tadi jalan sebagai root,
# bisa membuat file log/cache baru yang dimiliki root)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

php-fpm -D
nginx -g "daemon off;"
