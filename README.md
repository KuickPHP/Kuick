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
docker run -p 8080:80 kuickphp/message-broker
```
Now you can try it out by opening http://localhost:8080/
2. Specify more options ie. set storage to redis (of course you should specify the real redis address)
```
docker run -p 8080:80 \
  -e KUICK_MB_STORAGE_DSN="redis://127.0.0.1:6379" \
  kuickphp/message-broker
```
3. Let's define some channel permissions, below configuration will give:
- "read" permission to "news" channel for "john@pass" and "jane@pass"
- "write" permission to "news" channel for "john@pass" only
```
docker run -p 8080:80 \
  -e KUICK_MB_CONSUMER_MAP="news[]=john@pass&news[]=jane@pass" \
  -e KUICK_MB_PUBLISHER_MAP="news[]=john@pass" \
  kuickphp/message-broker
```
Now Kuick Message Broker runs on: http://localhost:8080/api/messages/news<br>
Posting the message by user "john@pass" to channel "news":
```
curl -X POST -H "Authorization: Bearer john@pass" -d 'Sample message' http://localhost:8080/api/message/news
```
Receiving messages from "news" channel, by "john@pass":
```
curl -H "Authorization: Bearer john@pass" http://localhost:8080/api/messages/news
```
Receiving a single message from "news" channel, by "john@pass", with automatic acknowledgement:
```
curl -H "Authorization: Bearer john@pass" "http://localhost:8080/api/message/news/{messageId}?autoack=true"
```
Manual acknowledgement:
```
curl -X POST -H "Authorization: Bearer john@pass" "http://localhost:8080/api/message/ack/news/{messageId}"
```
## Usage (Standalone)
1. Install PHP>8.2 + Composer
@TODO: finish this chapter