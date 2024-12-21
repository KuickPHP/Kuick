# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.4 \
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

ENV KUICK_APP_NAME=Kuick@Docker

COPY --link /etc/apache2 /etc/apache2
COPY --link bin bin
COPY --link config config
COPY --link public public
COPY --link composer.dist.json composer.json
COPY --link version.* public/

RUN mkdir -m 777 var

RUN set -eux; \
    composer install \ 
    --prefer-dist \
    --classmap-authoritative \
    --no-dev \
    --no-scripts \
    --no-plugins

######################
# Test runner target #
######################
FROM base AS test-runner

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage

COPY config/di config/di
COPY src src
COPY tests tests
COPY composer.json composer.json
COPY php* ./

RUN set -eux; \
    echo "apc.enable_cli=1" >> /etc/php/${PHP_VERSION}/mods-available/apcu.ini || \
    echo "apc.enable_cli=1" >> /etc/php/${PHP_VERSION/./}/mods-available/apcu.ini || \
    ; \
    composer install

#####################
# Dev server target #
#####################
FROM base AS dev-server

COPY ./etc/apache2 /etc/apache2

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=off \
    OPCACHE_VALIDATE_TIMESTAMPS=1

RUN echo "apc.enable_cli=1" >> /etc/php/${PHP_VERSION}/mods-available/apcu.ini

