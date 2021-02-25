FROM wardsonar:latest

RUN apt-get update \
    && apt-get install -y locales nginx \
    && locale-gen en_GB.UTF-8

# Redirect NGINX output to stdout and stderr.
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Copy configuration files over for installed software.
COPY ./nginx/sites-available/default /etc/nginx/sites-available/default

# Expose port 80 for HTTP access.
EXPOSE 80
