#!/usr/bin/env bash

# Run PHP-FPM as current user
if [ ! -z "$WWWUID" ]; then

    # Set UID of user "www-data"
    usermod -u $WWWUID www-data
fi

# Ensure /.composer exists and is writable
if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

# Run a command or supervisord
if [ $# -gt 0 ]; then
    # If we passed a command, run it instead
    exec gosu www-data "$@"
else
    # Otherwise start supervisord
    /usr/bin/supervisord
fi
