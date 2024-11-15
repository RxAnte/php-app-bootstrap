<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Log\LoggerInterface;
use Slim\Interfaces\ErrorHandlerInterface;

readonly class BootHttpMiddlewareConfig
{
    /**
     * Typehinting $customProductionErrorMiddlewareHandler in code produces an error
     *
     * @param callable|ErrorHandlerInterface|string|null         $customProductionErrorMiddlewareHandler
     * @param LoggerInterface|class-string<LoggerInterface>|null $productionErrorMiddlewareLogger
     */
    public function __construct(
        public bool $useProductionErrorMiddleware = true,
        public mixed $customProductionErrorMiddlewareHandler = null,
        public LoggerInterface|string|null $productionErrorMiddlewareLogger = null,
    ) {
    }
}
