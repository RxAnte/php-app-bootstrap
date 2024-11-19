<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Psr\Http\Message\ResponseInterface;
use RxAnte\AppBootstrap\Http\BootHttpMiddlewareConfig;
use RxAnte\AppBootstrap\TestSupport\RunHttpApp;
use Slim\Logger;
use Slim\Psr7\Response;

use function expect;
use function restore_error_handler;
use function restore_exception_handler;
use function test;
use function uses;

use const PHP_EOL;

uses()->group('Bootstrap', 'BootHttp');

test(
    'Slim boots with Whoops JsonResponseHandler',
    function (): void {
        $_SERVER['HTTP_ACCEPT'] = 'application/json';

        $response = (new RunHttpApp())->run();

        expect($response->getStatusCode())
            ->toBe(404)
            ->and((string) $response->getBody())
            ->toBe('{' . PHP_EOL . '    "message": "404 Not Found"' . PHP_EOL . '}')
            ->and($response->getHeaders()['Content-type'][0])
            ->toBe('application/json');

        restore_error_handler();
        restore_exception_handler();
    },
);

test(
    'Slim boots with Whoops PrettyPageHandler',
    function (): void {
        $_SERVER['HTTP_ACCEPT'] = 'text/html';

        $response = (new RunHttpApp())->run();

        expect($response->getStatusCode())
            ->toBe(404)
            ->and((string) $response->getBody())
            ->toStartWith('<!doctype html>')
            ->toContain('The requested resource could not be found')
            ->and($response->getHeaders()['Content-type'][0])
            ->toBe('text/html');

        restore_error_handler();
        restore_exception_handler();
    },
);

test(
    'Slim boots with custom error handler',
    function (): void {
        $response = (new RunHttpApp())->run(new BootHttpMiddlewareConfig(
            productionErrorMiddlewareLogger: Logger::class,
            customProductionErrorMiddlewareHandler: function (): ResponseInterface {
                return (new Response())->withStatus(123);
            },
        ));

        expect($response->getStatusCode())->toBe(123);

        restore_error_handler();
        restore_exception_handler();
    },
);
