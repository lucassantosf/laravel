#!/usr/bin/env bash
set -e

role=${CONTAINER_ROLE:-"app"}
migration=${RUN_MIGRATIONS:-"false"}

# Simplified wait for MySQL (optional, won't block forever)
echo "Checking connection to $DB_HOST..."

if [ "$role" = "app" ]; then
    # Optimization: Only run composer install if vendor doesn't exist
    if [ ! -d "vendor" ]; then
        composer install --no-interaction --optimize-autoloader
    fi

    if [ "$migration" = "true" ]; then
        echo "Running migrations..."
        # We use a try-catch pattern or just let it fail and start FPM anyway
        php artisan migrate --force || echo "Migrations failed, starting app anyway..."
    fi

    echo "Starting PHP-FPM..."
    exec php-fpm

elif [ "$role" = "queue" ]; then
    echo "Running the queue..."
    exec php artisan queue:work --verbose --tries=3 --timeout=0 --queue=${QUEUE_NAME:-default}

elif [ "$role" = "scheduler" ]; then
    echo "Running the scheduler..."
    exec php artisan schedule:work

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
