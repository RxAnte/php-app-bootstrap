<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

use function array_merge;
use function call_user_func_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification

readonly class RequestResponseCustom implements InvocationStrategyInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments,
    ): ResponseInterface {
        foreach ($routeArguments as $k => $v) {
            $request = $request->withAttribute($k, $v);
        }

        $event = new RequestResponseEvent([$routeArguments]);

        $this->eventDispatcher->dispatch($event);

        return call_user_func_array(
            $callable,
            array_merge(
                [
                    $request,
                    $response,
                ],
                $event->callableArguments,
            ),
        );
    }
}
