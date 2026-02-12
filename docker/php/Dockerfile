FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip sqlite3 libzip-dev libpng-dev libjpeg-dev libpq-dev libsqlite3-dev

RUN docker-php-ext-configure gd --with-jpeg

RUN docker-php-ext-install -j$(nproc) pdo zip gd fileinfo pdo_pgsql pgsql pcntl pdo_sqlite

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www