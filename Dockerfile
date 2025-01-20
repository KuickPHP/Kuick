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

ENV KUICK_APP_NAME=Kuick@Docker \
    OPCACHE_VALIDATE_TIMESTAMPS=0

COPY --link etc/apache2 /etc/apache2
COPY --link bin bin
COPY --link public public
# example distribution files
COPY --link distribution/composer.json composer.json
COPY --link config config
COPY --link version.* public/

RUN set -eux; \
    mkdir -m 777 var; \
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
    XDEBUG_MODE=coverage \
    OPCACHE_VALIDATE_TIMESTAMPS=1

# debian & alpine have different paths for apcu.ini
RUN set -eux; \
    echo "apc.enable_cli=1" >> /etc/php/${PHP_VERSION}/mods-available/apcu.ini || \
    echo "apc.enable_cli=1" >> /etc/php${PHP_VERSION/./}/conf.d/apcu.ini

#####################
# Dev server target #
#####################
FROM test-runner AS dev-server

COPY ./etc/apache2 /etc/apache2

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage \
    OPCACHE_VALIDATE_TIMESTAMPS=1
