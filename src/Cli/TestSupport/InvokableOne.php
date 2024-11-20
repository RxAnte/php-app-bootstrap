<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli\TestSupport;

// @codeCoverageIgnoreStart
readonly class InvokableOne
{
    public function __invoke(): void
    {
    }
}
// @codeCoverageIgnoreEnd
