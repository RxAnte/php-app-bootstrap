<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use function dirname;

readonly class SrcDir
{
    public const PATH = __DIR__;

    public static function testsCacheDir(): string
    {
        return dirname(self::PATH) . '/tests/cache';
    }
}
