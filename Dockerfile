FROM php:7.2-fpm-alpine3.7

# MySQL Extensions for PHP
RUN docker-php-ext-install pdo pdo_mysql

# Copy the ICMS Source code into the image
COPY src/ /var/www/icms/

# Add the nginx user so that we can give ownership
# of all our files to it
RUN addgroup -S nginx && adduser -S -g nginx nginx
RUN chown -R nginx:nginx /var/www/icms/
USER nginx
