# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.3

###################################################################
# Base PHP target                                                 #
###################################################################
FROM milejko/php:${PHP_VERSION}-apache AS base

###################################################################
# Distribution target (ie. for production environments)           #
###################################################################
FROM base AS dist

# Important performance hint:
# KUICK_APP_ENV=prod should be defined here, or via environment variables
# .env* files shouldn't be used in production
ENV KUICK_APP_ENV=prod \
    KUICK_APP_NAME=KuickMB \
    KUICK_APP_CHARSET=UTF-8 \
    KUICK_APP_LOCALE=en_US.utf-8 \
    KUICK_APP_TIMEZONE=UTC \
    KUICK_APP_MONOLOG_LEVEL=NOTICE

COPY --link ./bin/console ./bin/console
COPY --link ./etc/di ./etc/di
COPY --link ./etc/*.php ./etc/
COPY --link ./etc/apache2 /etc/apache2
COPY --link ./src ./src
COPY --link ./public/index.php ./public/index.php
COPY --link ./composer.json .
COPY --link ./version.* ./public/

RUN set -eux; \
    composer install \ 
    --prefer-dist \
    --no-dev \
    --classmap-authoritative \
    --no-plugins

###################################################################
# Test runner target                                              #
###################################################################
FROM dist AS test-runner

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage \
    KUICK_APP_ENV=test

COPY --link ./tests ./tests
COPY --link ./php* .

RUN set -eux; \
    composer install

###################################################################
# Dev server target                                               #
###################################################################
FROM base AS dev-server

COPY ./etc/apache2 /etc/apache2

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage \
    OPCACHE_VALIDATE_TIMESTAMPS=1
