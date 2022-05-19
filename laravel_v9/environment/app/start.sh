#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-"app"}
migration=${RUN_MIGRATIONS:-"false"}

if [ "$role" = "app" ]; then

    composer update --no-plugins
    composer install

    if [ "$migration" = true ]; then
        php artisan migrate
    fi

    sudo php artisan cache:clear
    exec php-fpm

elif [ "$role" = "default-queue" ]; then

    echo "Running default queue..."
    php artisan queue:work --verbose --tries=1 --timeout=3600 --queue=DEFAULT

elif [ "$role" = "email-queue" ]; then

    echo "Running email queue..."
    php artisan queue:work --verbose --tries=1 --timeout=3600 --queue=EMAIL

elif [ "$role" = "scheduler" ]; then

    while [ true ]
    do
      php artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
