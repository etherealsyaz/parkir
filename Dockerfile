# ===== Stage 1: Build frontend assets =====
FROM node:20 AS frontend-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ===== Stage 2: PHP application =====
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/nginx-fajrina.conf /etc/nginx/sites-available/default
COPY . .

# Copy hasil build asset dari stage 1
COPY --from=frontend-builder /app/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint-fajrina.sh /entrypoint-fajrina.sh
RUN chmod +x /entrypoint-fajrina.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint-fajrina.sh"]
