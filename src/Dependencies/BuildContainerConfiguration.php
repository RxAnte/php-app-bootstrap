<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

readonly class BuildContainerConfiguration
{
    public function __construct(
        public string|null $enableCompilationToDir = null,
        public string|null $writeProxiesToDir = null,
    ) {
    }
}
