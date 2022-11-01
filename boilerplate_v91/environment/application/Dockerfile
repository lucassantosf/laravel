FROM php:7.4-fpm

RUN apt update && apt install -y libzip-dev libpng-dev zip

RUN docker-php-ext-install tokenizer mysqli pdo_mysql zip gd 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Download the desired package(s)
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/msodbcsql18_18.1.1.1-1_amd64.apk
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/mssql-tools18_18.1.1.1-1_amd64.apk

#(Optional) Verify signature, if 'gpg' is missing install it using 'apk add gnupg':
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/msodbcsql18_18.1.1.1-1_amd64.sig
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/mssql-tools18_18.1.1.1-1_amd64.sig

RUN curl https://packages.microsoft.com/keys/microsoft.asc | gpg --import -
RUN gpg --verify msodbcsql18_18.1.1.1-1_amd64.sig msodbcsql18_18.1.1.1-1_amd64.apk
RUN gpg --verify mssql-tools18_18.1.1.1-1_amd64.sig mssql-tools18_18.1.1.1-1_amd64.apk

#Install the package(s)
RUN apk add --allow-untrusted msodbcsql18_18.1.1.1-1_amd64.apk
RUN apk add --allow-untrusted mssql-tools18_18.1.1.1-1_amd64.ap

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www/html
COPY --chown=www:www . /var/www/html

COPY /environment/application/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

USER www

CMD ["/usr/local/bin/start"]
