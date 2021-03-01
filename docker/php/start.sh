#!/usr/bin/env bash

# Run PHP-FPM as current user
if [ ! -z "$WWWUID" ]; then
    echo "Set www-data UID to $WWWUID"
    # Set UID of user "www-data"
    usermod -u $WWWUID www-data
fi

# Ensure /.composer exists and is writable
if [ ! -d ${PWD}/.composer ]; then
    echo "Create .composer directory in $PWD"
    mkdir ${PWD}/.composer
    export COMPOSER_HOME=${PWD}/.composer
fi

chmod -R ugo+rw ${PWD}/.composer

# Run a command or supervisord
if [ $# -gt 0 ]; then
    if [ "$CONTAINER_ENV" == "local" ]; then
        echo "Running $@ as www-data"
        export XDG_CONFIG_HOME=${PWD}
        # If we passed a command, run it instead
        exec gosu www-data "$@"
    else
        exec "$@"
    fi
else
    # Otherwise start supervisord

    echo "Install dependencies.."
    composer install

    echo "Run migrations..."
    php /var/www/html/artisan migrate --force

    echo "Cache config and routes..."
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan route:cache

    /usr/bin/supervisord
fi
