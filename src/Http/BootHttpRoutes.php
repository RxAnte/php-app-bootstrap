<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Slim\App;

readonly class BootHttpRoutes
{
    /** @param App<ContainerInterface> $app */
    public function __construct(
        private App $app,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function applyRoutes(): BootHttpMiddleware
    {
        $this->eventDispatcher->dispatch(new ApplyRoutesEvent(
            $this->app,
        ));

        return new BootHttpMiddleware(
            $this->app,
            $this->eventDispatcher,
        );
    }
}
