# Use PHP 8.4 FPM as base image
FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libldap2-dev \
    default-mysql-client \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions using mlocati/php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache
RUN install-php-extensions ldap
RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
    && docker-php-ext-install redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Setup user and permissions
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Copy start script
COPY /environment/backend/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["/usr/local/bin/start"]
