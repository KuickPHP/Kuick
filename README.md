# Kuick PHP framework
[![Latest Version](https://img.shields.io/github/release/milejko/kuick.svg?cacheSeconds=14400)](https://github.com/milejko/kuick/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/kuick/framework.svg?cacheSeconds=14400)](https://packagist.org/packages/kuick/framework)
[![PHP 8.2](https://img.shields.io/badge/PHP-8.2-blue?logo=php&cacheSeconds=3600)](https://www.php.net/releases/8.2/en.php)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-green?logo=php&cacheSeconds=3600)](https://www.php.net/releases/8.3/en.php)
[![GitHub Actions CI](https://github.com/milejko/kuick/actions/workflows/ci.yml/badge.svg)](https://github.com/milejko/kuick/actions/workflows/ci.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg&cacheSeconds=14400)](LICENSE)

Kuick is an extremely low footprint PHP application framework, based on Symfony components, suitable for high workloads
Designed for developers seeking speed, efficiency, and flexibility in web application development.

## Usage (Docker)
Ready to deploy images you can find here: https://hub.docker.com/r/kuickphp/kuick/tags

1. Run using Docker
This example utilizes the smallest, Alpine distribution.
```
docker run -p 8080:80 kuickphp/kuick:1.0-alpine
```
Now you can try it out by opening http://localhost:8080/<br>

2. Examine sample routes:
- Homepage:
```
curl http://localhost:8080/
```
- Hello/ping:
```
curl http://localhost:8080/hello/John
```

3. Container runtime configuration:
- dev mode enabled
- custom app name
- custom localization (charset, locale, timezone)
- DEBUG log with microtime
- custom OPS API token
```
docker run -p 8080:80 \
    -e KUICK_APP_ENV=dev \
    -e KUICK_APP_NAME=ExampleApp \
    -e KUICK_APP_CHARSET=UTF-8 \
    -e KUICK_APP_LOCALE=pl_PL.utf-8 \
    -e KUICK_APP_TIMEZONE="Europe/Warsaw" \
    -e KUICK_APP_MONOLOG.USEMICROSECONDS=1 \
    -e KUICK_APP_MONOLOG_LEVEL=DEBUG \
    -e KUICK_OPS_GUARD_TOKEN=secret-token \
    kuickphp/kuick:1.0-alpine
```
OPS endpoint:
```
curl -H "Authorization: Bearer secret-token" http://localhost:8080/api/ops
```
## Usage (Standalone)
1. Install PHP>8.2 + Composer
@TODO: finish this chapter