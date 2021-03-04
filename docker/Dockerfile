ARG PHP=7.4

# Core development image
FROM php:${PHP}-fpm as core-app

ARG PHP

ARG WWWUID=1000

LABEL maintainer="Stuart Laverick"

# Environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# Set working directory
WORKDIR /var/www/html

# Set the time zone
RUN apt-get update \
    && apt-get install -y gnupg tzdata \
    && echo $TZ > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

# install the Packages and PHP extensions we need
RUN apt-get update \
    && apt-get install -y \
    apt-utils \
    build-essential \
    git \
    gosu \
    jpegoptim optipng pngquant gifsicle \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libwebp-dev \
    libxslt1-dev \
    locales \
    mariadb-client \
    supervisor \
    unzip \
    libzip-dev \
    && docker-php-ext-configure gd --enable-gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure zip --with-zip \
    && docker-php-ext-install -j$(nproc) bcmath intl gd mysqli opcache pdo_mysql pdo_pgsql soap xsl zip \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# install and Configure Xdebug
RUN pecl install xdebug-2.8.1 \
    && docker-php-ext-enable xdebug

# Install and configure PHP Redis
RUN pecl install redis-5.1.1 \
    && docker-php-ext-enable redis

# Configure Opcache
ADD php/opcache-recommended.ini /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
ADD php/local.ini /usr/local/etc/php/conf.d/local.ini

COPY supervisord.local.conf /etc/supervisor/conf.d/supervisord.conf
COPY start.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

# Expose port 9000 and start php-fpm server
EXPOSE 9000

ENTRYPOINT ["start-container"]

# Deployed image
FROM core-app

# Set working directory
WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y locales nginx \
    && locale-gen en_GB.UTF-8

# Redirect NGINX output to stdout and stderr.
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log \
    &&  echo "daemon off;" >> /etc/nginx/nginx.conf

# Copy configuration files over for installed software.
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY nginx/sites-available/default /etc/nginx/sites-available/default

# Copy in the application working directory
COPY app.tar app.tar

RUN tar -xf app.tar -C /var/www/html \
    && rm app.tar \
    && chown -R www-data:www-data /var/www/html

# Expose port 80 for HTTP access.
EXPOSE 80
