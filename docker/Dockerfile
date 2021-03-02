FROM wardsonar:latest

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
COPY ./nginx/sites-available/default /etc/nginx/sites-available/default

# Copy in the application working directory
COPY app.tar app.tar

RUN tar -xf app.tar -C /var/www/html \
    && rm app.tar \
    && chown -R www-data:www-data /var/www/html

# Expose port 80 for HTTP access.
EXPOSE 80
