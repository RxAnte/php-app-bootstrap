<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli\TestSupport;

// @codeCoverageIgnoreStart
readonly class InvokableTwo
{
    public function __invoke(): void
    {
    }
}
// @codeCoverageIgnoreEnd