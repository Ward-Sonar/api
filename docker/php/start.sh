#!/usr/bin/env bash

# Run PHP-FPM as current user
if [ ! -z "$WWWUID" ]; then
    echo "Set www-data UID to $WWWUID"
    # Set UID of user "www-data"
    usermod -u $WWWUID www-data
fi

# Ensure /.composer exists and is writable
if [ ! -d /.composer ]; then
    echo "Create .composer directory in $PWD"
    mkdir /.composer
    export COMPOSER_HOME=${PWD}/.composer
fi

chmod -R ugo+rw /.composer

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
    /usr/bin/supervisord
fi
