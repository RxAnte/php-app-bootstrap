<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\TestSupport;

use Psr\Http\Message\ResponseInterface;
use RxAnte\AppBootstrap\Boot;
use RxAnte\AppBootstrap\BootConfig;
use RxAnte\AppBootstrap\Dependencies\Bindings;
use RxAnte\AppBootstrap\Dependencies\RegisterBootstrapDependencies;
use RxAnte\AppBootstrap\Http\BootHttpMiddlewareConfig;
use Slim\ResponseEmitter;

use function assert;

readonly class RunHttpApp
{
    public function run(
        BootHttpMiddlewareConfig $config = new BootHttpMiddlewareConfig(),
    ): ResponseInterface {
        $responseEmitter = new class () extends ResponseEmitter {
            public ResponseInterface|null $response = null;

            public function emit(ResponseInterface $response): void
            {
                $this->response = $response;
            }
        };

        (new Boot())
            ->start(new BootConfig(
                isCli: false,
                useWhoopsErrorHandling: true,
            ))
            ->buildContainer(static function (
                Bindings $bindings,
            ) use ($responseEmitter): void {
                RegisterBootstrapDependencies::register($bindings);

                $bindings->addBinding(
                    ResponseEmitter::class,
                    $responseEmitter,
                );
            })
            ->registerEventSubscribers()
            ->buildHttpApplication()
            ->applyRoutes()
            ->applyMiddleware($config)
            ->runApplication();

        assert($responseEmitter->response !== null);

        return $responseEmitter->response;
    }
}
