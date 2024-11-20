<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http\TestSupport;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function expect;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification

readonly class RequestResponseCallableDefaultArguments
{
    /** @phpstan-ignore-next-line */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments,
    ): ResponseInterface {
        expect($request->getAttributes())->toBe([
            'foo' => 'bar',
            'baz' => 'bap',
        ]);

        expect($routeArguments)->toBe([
            'foo' => 'bar',
            'baz' => 'bap',
        ]);

        $response->getBody()->write(
            'RequestResponseCallableDefaultArguments',
        );

        return $response->withStatus(501);
    }
}
