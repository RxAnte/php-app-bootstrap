<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

readonly class CliConfig
{
    public function __construct(public string $cliAppName)
    {
    }
}
