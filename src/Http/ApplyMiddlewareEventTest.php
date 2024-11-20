<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Mockery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;

use function expect;
use function test;
use function uses;

uses()->group('Bootstrap', 'ApplyMiddlewareEvent');

test(
    'ApplyMiddlewareEvent::add accepts MiddlewareInterface implementation',
    function (): void {
        $request = Mockery::mock(ServerRequestInterface::class);

        $handler = Mockery::mock(RequestHandlerInterface::class);

        $response = Mockery::mock(ResponseInterface::class);

        $middleware = new class ($response) implements MiddlewareInterface {
            public function __construct(
                private readonly ResponseInterface $response,
            ) {
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler,
            ): ResponseInterface {
                return $this->response;
            }
        };

        $app = Mockery::mock(App::class);

        $app->expects('add')->with($middleware);

        (new ApplyMiddlewareEvent($app))->add($middleware);

        expect($middleware->process(
            $request,
            $handler,
        ))->toBe($response);
    },
);

test(
    'ApplyMiddlewareEvent::add accepts string',
    function (): void {
        $app = Mockery::mock(App::class);

        $app->expects('add')->with('foo-bar');

        (new ApplyMiddlewareEvent($app))->add('foo-bar');
    },
);

test(
    'ApplyMiddlewareEvent::add accepts callable',
    function (): void {
        $callable = function (): void {
        };

        $app = Mockery::mock(App::class);

        $app->expects('add')->with($callable);

        (new ApplyMiddlewareEvent($app))->add($callable);
    },
);
