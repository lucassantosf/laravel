#!/usr/bin/env bash
set -e

role=${CONTAINER_ROLE:-"app"}
migration=${RUN_MIGRATIONS:-"false"}

# Espera pelo MySQL
# Aguarda o MySQL estar aceitando conexões
until mysqladmin ping -hmysql -uroot -p${DB_PASSWORD} --silent; do
    echo "Aguardando o MySQL iniciar..."
    sleep 2
done

if [ "$role" = "app" ]; then
    composer install
    composer update --no-plugins

    if [ "$migration" = true ]; then
        php artisan migrate
    fi

    exec php-fpm

elif [ "$role" = "default-queue" ]; then
    echo "Running default queue..."
    php artisan queue:work --verbose --tries=1 --timeout=3600 --queue=DEFAULT

elif [ "$role" = "scheduler" ]; then
    while true; do
      php artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi