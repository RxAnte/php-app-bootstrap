<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestResponseEvent
{
    /** @param mixed[] $callableArguments */
    public function __construct(
        public array $callableArguments,
        public readonly ServerRequestInterface $request,
        public readonly ResponseInterface $response,
    ) {
    }
}
