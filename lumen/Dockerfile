FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
      libzip-dev \
      zip \
    && docker-php-ext-install zip

RUN docker-php-ext-install tokenizer mysqli pdo_mysql
