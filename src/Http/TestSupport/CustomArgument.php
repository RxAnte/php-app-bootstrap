<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http\TestSupport;

readonly class CustomArgument
{
    public function __construct(public string $foo)
    {
    }
}
