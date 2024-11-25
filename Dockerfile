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
# Always use environment variable of KUICK_APP_ENV
# .env files should be used only in dev/test modex
ENV KUICK_APP_ENV=prod

COPY --link ./bin/console ./bin/console
COPY --link ./etc/di ./etc/di
COPY --link ./etc/routes ./etc/routes
COPY --link ./etc/apache2 /etc/apache2
COPY --link ./src ./src
COPY --link ./public/index.php ./public/index.php
COPY --link ./composer.json .
COPY --link ./version.* .

RUN composer install --no-dev

###################################################################
# Test runner target                                              #
###################################################################
FROM dist AS test-runner

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage \
    KUICK_APP_ENV=test

COPY --link ./tests ./tests
COPY --link ./php* .

RUN composer install

###################################################################
# Dev server target                                               #
###################################################################
FROM base AS dev-server

# defined in .env .env.loca files
#ENV KUICK_APP_ENV=dev

COPY ./etc/apache2 /etc/apache2

ENV OPCACHE_VALIDATE_TIMESTAMPS=1
