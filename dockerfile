# syntax=docker/dockerfile:1

FROM composer:lts as prod-deps
WORKDIR /public_html
RUN --mount=type=bind,source=./public_html/composer.json,target=composer.json \
    --mount=type=bind,source=./public_html/composer.lock,target=composer.lock \
    --mount=type=bind,source=./public_html/web/composer.json,target=web/composer.json \
    --mount=type=bind,source=./public_html/web/composer.lock,target=web/composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM composer:lts as dev-deps
WORKDIR /public_html
RUN --mount=type=cache,target=/tmp/cache \
    --mount=type=bind,source=./public_html/composer.json,target=composer.json \
    --mount=type=bind,source=./public_html/composer.lock,target=composer.lock \
    --mount=type=bind,source=./public_html/web/composer.json,target=web/composer.json \
    --mount=type=bind,source=./public_html/web/composer.lock,target=web/composer.lock \
    composer install --no-interaction


FROM php:8.2-apache as base

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip
RUN docker-php-ext-install pdo pdo_mysql gd

RUN a2enmod rewrite headers
COPY ./public_html /var/www/html

FROM base as development
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./credentials.php /var/www/credentials.php




FROM base as final
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./credentials.php /var/www/credentials.php
USER www-data

