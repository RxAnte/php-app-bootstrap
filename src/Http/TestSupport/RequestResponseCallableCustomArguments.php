<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http\TestSupport;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function expect;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification

readonly class RequestResponseCallableCustomArguments
{
    /** @phpstan-ignore-next-line */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        CustomArgument $customArgument,
        array $routeArguments,
    ): ResponseInterface {
        expect($request->getAttributes())->toBe(
            ['foo' => 'bar'],
        );

        expect($routeArguments)->toBe(['foo' => 'bar']);

        $response->getBody()->write($customArgument->foo);

        return $response->withStatus(301);
    }
}
