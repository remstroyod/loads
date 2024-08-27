FROM php:8.1-fpm-alpine

ADD ./.docker/php/php.ini /usr/local/etc/php/conf.d/40-custom.ini
ADD ./.docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
ADD ./.docker/php/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
ADD ./.docker/redis/redis.conf /usr/local/etc/redis.conf

WORKDIR /var/www/html

COPY .env /var/www/html/.env

RUN apk add --update linux-headers
RUN apk add --update libzip-dev curl-dev curl libmcrypt libmcrypt-dev openssh-client icu-dev libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf openssl &&\
    docker-php-source extract &&\
    pecl install xdebug redis &&\
    docker-php-ext-enable xdebug &&\
    docker-php-ext-enable redis  &&\
    docker-php-ext-configure gd --with-freetype --with-jpeg &&\
    docker-php-ext-install curl pdo pdo_mysql zip gd pcntl && \
    apk del gcc g++ &&\
    rm -rf /var/cache/apk/*

RUN apk add --update nodejs npm
RUN apk add --no-cache bash

# Установка supervisor и cronie
RUN apk add --no-cache supervisor dcron

COPY ./.docker/supervisor/supervisord.conf /etc/supervisord.conf
COPY ./.docker/cron/laravel /etc/crontabs/root

RUN usermod -u 1000 www-data; \
    chown -R www-data:www-data /var/www/html

# Запуск supervisord и cron
CMD /usr/bin/supervisord -c /etc/supervisord.conf
