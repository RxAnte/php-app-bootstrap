<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Slim\App;

use function assert;
use function is_string;

readonly class BootHttpMiddleware
{
    /** @param App<ContainerInterface> $app */
    public function __construct(
        private App $app,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function applyMiddleware(
        BootHttpMiddlewareConfig $config,
    ): Run {
        $this->eventDispatcher->dispatch(new ApplyMiddlewareEvent(
            $this->app,
        ));

        if ($config->useProductionErrorMiddleware) {
            $this->applyProductionErrorMiddleware($config);
        }

        return new Run($this->app);
    }

    private function applyProductionErrorMiddleware(
        BootHttpMiddlewareConfig $config,
    ): void {
        $logger = $config->productionErrorMiddlewareLogger;

        if (is_string($logger)) {
            $logger = $this->getLoggerFromString($logger);
        }

        $errorMiddleware = $this->app->addErrorMiddleware(
            false,
            false,
            false,
            $logger,
        );

        if ($config->customProductionErrorMiddlewareHandler === null) {
            return;
        }

        $errorMiddleware->setDefaultErrorHandler(
            $config->customProductionErrorMiddlewareHandler,
        );
    }

    private function getLoggerFromString(string $loggerClass): LoggerInterface
    {
        $logger = $this->app->getContainer()->get($loggerClass);

        assert($logger instanceof LoggerInterface);

        return $logger;
    }
}
