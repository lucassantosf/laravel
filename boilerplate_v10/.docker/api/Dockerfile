FROM public.ecr.aws/docker/library/php:7.4-apache

WORKDIR /var/www/html
COPY . .

RUN apt update && apt install -y libzip-dev libpng-dev zip cron nano
RUN docker-php-ext-install tokenizer mysqli pdo_mysql zip gd

COPY --from=public.ecr.aws/docker/library/composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install

RUN a2enmod rewrite
RUN a2enmod remoteip

COPY ./.docker/api/apache2.conf /etc/apache2/apache2.conf
COPY ./.docker/api/000-default.conf /etc/apache2/sites-available/000-default.conf
# COPY remoteip.conf /etc/apache2/conf-available/remoteip.conf
COPY ./environment/custom.ini /usr/local/etc/php/conf.d/custom.ini

RUN find . -type f -exec chmod 664 {} \;   
RUN find . -type d -exec chmod 775 {} \;

RUN usermod -u 1000 www-data

RUN chgrp -R www-data storage 
RUN chmod -R ug+rwx storage

EXPOSE 80