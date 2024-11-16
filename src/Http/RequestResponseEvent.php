<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

class RequestResponseEvent
{
    /** @param mixed[] $callableArguments */
    public function __construct(public array $callableArguments)
    {
    }
}
