<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

readonly class BootConfig
{
    public function __construct(
        public bool $isCli = false,
        public bool $useWhoopsErrorHandling = false,
    ) {
    }
}
