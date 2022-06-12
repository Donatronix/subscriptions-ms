FROM webdevops/php-nginx:8.1-alpine
LABEL Maintainer="Ihor Porokhnenko <ihor.porokhnenko@gmail.com>"
LABEL Description="Lightweight container with Nginx & PHP-FPM 8 based on Alpine Linux."

# Do a single run command to make the intermediary containers smaller.
RUN set -ex

## Update package list
RUN apk update

## Install packages necessary during the build phase
RUN apk --no-cache add \
    mc \
    nano

## Clean apk cache after all installed packages
RUN rm -rf /var/cache/apk/*

# Configure nginx
COPY config/vhost.conf /opt/docker/etc/nginx/vhost.conf
COPY config/vhost.ssl.conf /opt/docker/etc/nginx/vhost.ssl.conf
COPY config/vhost.common.d/ /opt/docker/etc/nginx/vhost.common.d/

## Copy existing application contents to workdir
COPY --chown=nginx:nginx ./web /var/www/html
COPY --chown=nginx:nginx ./sumra-sdk /var/www/sumra-sdk

## Set work directory
WORKDIR /var/www/html

## Update env
RUN cp -f .env.production .env
RUN rm -rf /var/www/html/.env.production

## Set writable dirs
RUN chown -R nginx:nginx /var/www/html
RUN chmod -R 777 /var/www/html/storage/

## Composer packages install
RUN composer install

#COPY ./entrypoint.sh /opt/docker/bin/entrypoint.d/service-init.sh
#ENTRYPOINT ["/bin/bash", "-c", "/opt/docker/bin/entrypoint.d/service-init.sh" ]

#COPY ./entrypoint.sh /service-init.sh
#ENTRYPOINT ["/bin/bash", "-c", "./service-init.sh" ]


COPY ./entrypoint.sh /service-init.sh
RUN sed -i 's/\r$//g' /service-init.sh
RUN chmod +x /service-init.sh
ENTRYPOINT ["/service-init.sh"]


