FROM php:8.2.5-cli

RUN apt-get update -y && apt-get -y install git
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip curl sockets pdo_pgsql redis
# TODO: enable opcache

COPY --from=ghcr.io/roadrunner-server/roadrunner /usr/bin/rr /usr/local/bin/rr
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /usr/src/api

COPY ./composer.json ./composer.json
COPY ./composer.lock ./composer.lock

RUN composer install
RUN composer dump-autoload

CMD rr serve