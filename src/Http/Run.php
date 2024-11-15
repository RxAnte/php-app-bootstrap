<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\ResponseEmitter;

use function assert;

readonly class Run
{
    /** @param App<ContainerInterface> $app */
    public function __construct(private App $app)
    {
    }

    public function runApplication(): void
    {
        $container = $this->app->getContainer();

        assert($container instanceof ContainerInterface);

        $responseEmitter = $container->get(ResponseEmitter::class);

        assert($responseEmitter instanceof ResponseEmitter);

        $request = $container->get(ServerRequestInterface::class);

        assert($request instanceof ServerRequestInterface);

        $responseEmitter->emit($this->app->handle($request));
    }
}
