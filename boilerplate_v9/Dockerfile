FROM php:7.4-fpm
MAINTAINER Lucas Santos

RUN apt update && apt install -y libzip-dev libpng-dev zip

RUN docker-php-ext-install tokenizer mysqli pdo_mysql zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# RUN groupadd -g 1000 www
# RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www
# COPY --chown=www:www . /var/www

COPY /environment/application/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

# USER www
# CMD ["/usr/local/bin/start"]

EXPOSE 9000