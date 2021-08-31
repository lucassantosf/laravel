FROM php:7.4-fpm

#COPY ./yetz-cron /etc/cron.d/yetz-cron
#RUN chmod 0644 /etc/cron.d/yetz-cron

RUN apt update && apt install -y libzip-dev libpng-dev zip cron nano

RUN docker-php-ext-install tokenizer mysqli pdo_mysql zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www/html
COPY --chown=www:www . /var/www/html

COPY ./start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

USER www

CMD ["/usr/local/bin/start"]
