FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y \
        git unzip curl default-mysql-client \
        libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libonig-dev libxml2-dev \
        libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql mbstring zip bcmath gd exif pcntl sockets \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN git config --global --add safe.directory /var/www/parser-test

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
