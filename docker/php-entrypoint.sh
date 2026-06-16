#!/bin/sh
set -e

cd /app

if [ ! -f vendor/autoload.php ]; then
    echo "vendor/autoload.php missing - running composer install..."
    if ! composer install --no-interaction --no-progress --ignore-platform-reqs; then
        echo "composer install failed - check network and composer.json"
        exit 1
    fi
fi

if [ ! -f vendor/autoload.php ]; then
    echo "vendor/autoload.php still missing after composer install"
    exit 1
fi

exec php-fpm
