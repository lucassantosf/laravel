#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-"app"}
migration=${RUN_MIGRATIONS:-"false"}

if [ "$role" = "app" ]; then

    composer install
    composer update --no-plugins

    if [ "$migration" = true ]; then
        php artisan migrate
    fi

    # sudo php artisan cache:clear
    exec php-fpm

elif [ "$role" = "default-queue" ]; then

    echo "Running Supervisor for queue work..."
    supervisord -c /etc/supervisor/conf.d/supervisord.conf
    
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
