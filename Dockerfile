FROM php:7-fpm

RUN docker-php-ext-install pdo pdo_mysql
# RUN pecl install xdebug
# RUN docker-php-ext-enable xdebug

COPY src/ /icms/