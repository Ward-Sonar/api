#!/usr/bin/env bash

# Run PHP-FPM as current user
if [ ! -z "$WWWUID" ]; then
    sed -i "s/user\ \=.*/user\ \= $WWWUID/g" /etc/php/7.4/fpm/pool.d/www.conf

    # Set UID of user "www"
    usermod -u $WWWUID www
fi

# Ensure /.composer exists and is writable
if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

# Run a command or supervisord
if [ $# -gt 0 ]; then
    # If we passed a command, run it instead
    exec gosu www "$@"
else
    # Otherwise start supervisord
    /usr/bin/supervisord
fi
