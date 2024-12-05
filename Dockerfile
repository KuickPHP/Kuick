# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.3 \
    SERVER_VARIANT=apache \
    OS_VARIANT=noble

###################
# Base PHP target #
###################
FROM milejko/php:${PHP_VERSION}-${SERVER_VARIANT}-${OS_VARIANT} AS base

#########################################################
# Distribution target (ie. for production environments) #
#########################################################
FROM base AS dist

# Important performance hint:
# KUICK_APP_ENV=prod should be defined here, or via environment variables
# .env* files shouldn't be used in production
ENV KUICK_APP_ENV=prod \
    KUICK_APP_NAME=Kuick@Docker

COPY --link /etc/apache2 /etc/apache2
COPY --link bin ./bin
COPY --link etc ./etc
COPY --link public ./public
COPY --link composer.dist.json composer.json
COPY --link version.* ./public/

RUN mkdir -m 777 var

RUN set -eux; \
    composer install \ 
    --prefer-dist \
    --ignore-platform-reqs \
    --no-dev \
    --classmap-authoritative \
    --no-scripts \
    --no-plugins

######################
# Test runner target #
######################
FROM base AS test-runner

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage

COPY ./etc/di ./etc/di
COPY ./src ./src
COPY ./tests ./tests
COPY ./composer.json ./composer.json
COPY ./php* .    

RUN set -eux; \
    composer install

#####################
# Dev server target #
#####################
FROM base AS dev-server

COPY ./etc/apache2 /etc/apache2

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=develop \
    OPCACHE_VALIDATE_TIMESTAMPS=1
