###################  This Dockerfile is made of two parts:  ###################
#
# 1. The first part extends a PHP composer image so that you can install the application's dependencies.

FROM composer:latest as build

LABEL maintainer "Ihor Porokhnenko <ihor.porokhnenko@gmail.com>"

RUN apk update && apk add --no-cache \
        php8-intl \
        icu-dev

#RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl sockets bcmath pdo pdo_mysql intl gd exif

COPY ./web      /app
COPY ./pubsub   /pubsub
COPY ./json-api /json-api
#COPY ./baum    /baum

WORKDIR /app
RUN rm -rf .idea
RUN composer -v install
RUN composer -v update

# 2. The second part creates a final Docker image with an Apache web server to serve the application

FROM php:8.0.6-apache-buster

COPY --from=build /app /var/www/html
COPY --from=build /pubsub /var/www/pubsub
COPY --from=build /json-api /var/www/json-api

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
#COPY conf/laravel-echo-server.json /var/www/html
#RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && apt install -y npm && npm i -g laravel-echo-server

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite ssl headers

# make sure apt is up to date
RUN apt update --fix-missing && apt upgrade -y

RUN apt install -y \
        mc \
        curl \
        openssh-client \
        build-essential \
        libssl-dev \
        zlib1g-dev \
        libicu-dev

RUN docker-php-ext-configure gd

RUN docker-php-ext-install -j$(nproc) gd pdo_mysql intl sockets bcmath exif

RUN pecl install xdebug-3.0.3
#RUN docker-php-ext-configure xdebug
RUN docker-php-ext-enable xdebug gd exif

#RUN cd /var/www/html && php artisan l5-swagger:generate

RUN echo "Listen 8080" > /etc/apache2/ports.conf
RUN echo "Listen 8443" >> /etc/apache2/ports.conf
USER www-data
EXPOSE 8443 8080
