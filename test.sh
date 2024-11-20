#!/usr/bin/env bash

php -d memory_limit=4G ./vendor/bin/pest --cache-directory tests/cache/ "${@}"
