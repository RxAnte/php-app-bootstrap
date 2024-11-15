<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;

readonly class ApplyMiddlewareEvent
{
    /** @param App<ContainerInterface> $app */
    public function __construct(private App $app)
    {
    }

    public function add(
        MiddlewareInterface|string|callable $middleware,
    ): self {
        $this->app->add($middleware);

        return $this;
    }
}
