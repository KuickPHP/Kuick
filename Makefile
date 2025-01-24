######################
# Default parameters #
######################
IMAGE_NAME := kuickphp/kuick
FORCE_BUILD := 0

.DEFAULT_GOAL := test
.PHONY: * # ignore files named like targets

version.txt:
	git describe --always --tags > version.txt

test: version.txt
	# generate CI_TAG to avoid concurrent run collisions
	$(eval CI_TAG := $(IMAGE_NAME):$(shell date +%s%N))
	docker build --target=test-runner --tag $(CI_TAG) .
	docker run --rm -v ./:/var/www/html $(CI_TAG) sh -c "composer up && composer test:all"
	docker image rm $(CI_TAG)

build: version.txt
	docker build --no-cache --target=dist --platform "linux/amd64,linux/arm64" --tag=$(IMAGE_NAME):$(shell cat version.txt) .
