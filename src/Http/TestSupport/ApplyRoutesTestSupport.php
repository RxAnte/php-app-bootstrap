<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http\TestSupport;

use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;
use Slim\Routing\RouteCollectorProxy;

use function assert;

readonly class ApplyRoutesTestSupport
{
    public function setRoutes(ApplyRoutesEvent $applyRoutesEvent): void
    {
        $applyRoutesEvent->get('/foo-get', function () {
            return $this->createResponse('get-foo');
        });

        $applyRoutesEvent->post('/foo-post', function () {
            return $this->createResponse('post-foo');
        });

        $applyRoutesEvent->put('/foo-put', function () {
            return $this->createResponse('put-foo');
        });

        $applyRoutesEvent->patch('/foo-patch', function () {
            return $this->createResponse('patch-foo');
        });

        $applyRoutesEvent->delete('/foo-delete', function () {
            return $this->createResponse('delete-foo');
        });

        $applyRoutesEvent->options('/foo-options', function () {
            return $this->createResponse('options-foo');
        });

        $applyRoutesEvent->any('/foo-any', function () {
            return $this->createResponse('any-foo');
        });

        $applyRoutesEvent->map(
            ['GET', 'POST'],
            '/foo-map',
            function () {
                return $this->createResponse('map-foo');
            },
        );

        $applyRoutesEvent->group(
            '/foo-group',
            function (RouteCollectorProxy $routeCollector): void {
                $routeCollector->get(
                    '/foo-group-route-test',
                    function () {
                        return $this->createResponse(
                            'group-route-test-foo',
                        );
                    },
                );
            },
        );

        $applyRoutesEvent->redirect(
            '/foo-redirect',
            '/redirect-foo',
            123,
        );
    }

    public function createRequest(string $path, string $method): Request
    {
        $uri = new Uri(
            'https',
            'testing.dev',
            443,
            $path,
            '',
            '',
            '',
            '',
        );

        $headers = new Headers();

        $stream = Mockery::mock(StreamInterface::class);

        assert($stream instanceof StreamInterface);

        return new Request(
            $method,
            $uri,
            $headers,
            [],
            [],
            $stream,
            [],
        );
    }

    private function createResponse(string $bodyText): ResponseInterface
    {
        $body = Mockery::mock(StreamInterface::class);

        assert($body instanceof MockInterface);

        $body->shouldReceive('getContents')
            ->andReturn($bodyText);

        $response = Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('getBody')->andReturn($body);

        assert($response instanceof ResponseInterface);

        return $response;
    }
}
