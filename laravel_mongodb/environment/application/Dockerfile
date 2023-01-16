FROM php:8.0-fpm

RUN apt update && apt install -y libzip-dev libpng-dev zip

RUN docker-php-ext-install tokenizer mysqli pdo_mysql zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    build-essential \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www/html
COPY --chown=www:www . /var/www/html

COPY /environment/application/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

USER www

CMD ["/usr/local/bin/start"]
