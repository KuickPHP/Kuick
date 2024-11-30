# Kuick PHP framework
[![Latest Version](https://img.shields.io/github/release/milejko/kuick.svg)](https://github.com/milejko/kuick/releases)
[![GitHub Actions CI](https://github.com/milejko/kuick/actions/workflows/ci.yml/badge.svg)](https://github.com/milejko/kuick/actions/workflows/ci.yml)
[![Coverage](https://raw.githubusercontent.com/milejko/kuick/refs/heads/main/badge-coverage.svg)](https://github.com/milejko/kuick/tree/main/tests)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/kuick/framework.svg)](https://packagist.org/packages/kuick/framework)

Kuick is an extremely low footprint PHP application framework, based on Symfony components, suitable for high workloads
Designed for developers seeking speed, efficiency, and flexibility in web application development.

## Usage (Docker)
Ready to deploy images you can find here: https://hub.docker.com/r/milejko/kuick/tags

1. Run using Docker
```
docker run -p 8080:80 milejko/kuick
```
Now you can try it out by opening http://localhost:8080/
2. Check the example route:
Homepage:
```
curl http://localhost:8080/hello/John
```
Hello/ping:
```
curl http://localhost:8080/hello/John
```
OPS API:
```
curl -H "Authorization: Bearer let-me-in" http://localhost:8080/api/ops
```
## Usage (Standalone)
1. Install PHP>8.2 + Composer
@TODO: finish this chapter