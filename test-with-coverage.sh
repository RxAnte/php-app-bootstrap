#!/usr/bin/env bash

rm -rf tests/cache/di-compiled;

XDEBUG_MODE=coverage php -d memory_limit=4G ./vendor/bin/pest --coverage --cache-directory tests/cache/ --coverage --coverage-html tests/code-coverage/ "${@}"
