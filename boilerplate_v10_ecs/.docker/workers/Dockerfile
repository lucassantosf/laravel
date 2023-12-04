FROM public.ecr.aws/docker/library/php:7.4

WORKDIR /var/www/html
COPY . .

RUN apt-get update && apt-get install -y supervisor
RUN apt-get update && apt-get install -y libzip-dev libpng-dev supervisor zip
RUN docker-php-ext-install tokenizer mysqli pdo_mysql zip gd

COPY --from=public.ecr.aws/docker/library/composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install

COPY ./environment/custom.ini /usr/local/etc/php/conf.d/custom.ini
ADD .docker/workers /etc/supervisor/conf.d

CMD ["/usr/bin/supervisord"]