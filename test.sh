#!/usr/bin/env bash

rm -rf tests/cache/di-compiled;

php -d memory_limit=4G ./vendor/bin/pest --cache-directory tests/cache/ "${@}"
