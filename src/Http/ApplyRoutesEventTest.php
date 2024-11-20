<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use RxAnte\AppBootstrap\Dependencies\BuildContainer;
use RxAnte\AppBootstrap\Http\TestSupport\ApplyRoutesTestSupport;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Throwable;

use function expect;
use function test;
use function uses;

uses()->group('Bootstrap', 'ApplyRoutesEvent');

test(
    'Apply routes event routing methods are available',
    function (): void {
        $app = AppFactory::create();

        /** @phpstan-ignore-next-line */
        $applyRoutesEvent = new ApplyRoutesEvent($app);

        $testSupport = new ApplyRoutesTestSupport();

        $testSupport->setRoutes($applyRoutesEvent);

        $fooGetResponse = $app->handle($testSupport->createRequest(
            '/foo-get',
            'GET',
        ));
        expect($fooGetResponse->getBody()->getContents())
            ->toBe('get-foo');

        $fooPostResponse = $app->handle($testSupport->createRequest(
            '/foo-post',
            'POST',
        ));
        expect($fooPostResponse->getBody()->getContents())
            ->toBe('post-foo');

        $fooPutResponse = $app->handle($testSupport->createRequest(
            '/foo-put',
            'PUT',
        ));
        expect($fooPutResponse->getBody()->getContents())
            ->toBe('put-foo');

        $fooPatchResponse = $app->handle($testSupport->createRequest(
            '/foo-patch',
            'PATCH',
        ));
        expect($fooPatchResponse->getBody()->getContents())
            ->toBe('patch-foo');

        $fooDeleteResponse = $app->handle($testSupport->createRequest(
            '/foo-delete',
            'DELETE',
        ));
        expect($fooDeleteResponse->getBody()->getContents())
            ->toBe('delete-foo');

        $fooOptionsResponse = $app->handle($testSupport->createRequest(
            '/foo-options',
            'OPTIONS',
        ));
        expect($fooOptionsResponse->getBody()->getContents())
            ->toBe('options-foo');

        // Any
        $fooAnyResponseGet = $app->handle($testSupport->createRequest(
            '/foo-any',
            'GET',
        ));
        expect($fooAnyResponseGet->getBody()->getContents())
            ->toBe('any-foo');

        $fooAnyResponsePost = $app->handle($testSupport->createRequest(
            '/foo-any',
            'POST',
        ));
        expect($fooAnyResponsePost->getBody()->getContents())
            ->toBe('any-foo');

        $fooAnyResponsePut = $app->handle($testSupport->createRequest(
            '/foo-any',
            'PUT',
        ));
        expect($fooAnyResponsePut->getBody()->getContents())
            ->toBe('any-foo');

        $fooAnyResponsePatch = $app->handle($testSupport->createRequest(
            '/foo-any',
            'PATCH',
        ));
        expect($fooAnyResponsePatch->getBody()->getContents())
            ->toBe('any-foo');

        $fooAnyResponseDelete = $app->handle($testSupport->createRequest(
            '/foo-any',
            'DELETE',
        ));
        expect($fooAnyResponseDelete->getBody()->getContents())
            ->toBe('any-foo');

        $fooAnyResponseOptions = $app->handle($testSupport->createRequest(
            '/foo-any',
            'OPTIONS',
        ));
        expect($fooAnyResponseOptions->getBody()->getContents())
            ->toBe('any-foo');

        // Map
        $fooMapResponseGet = $app->handle($testSupport->createRequest(
            '/foo-map',
            'GET',
        ));
        expect($fooMapResponseGet->getBody()->getContents())
            ->toBe('map-foo');

        $fooMapResponseGet = $app->handle($testSupport->createRequest(
            '/foo-map',
            'POST',
        ));
        expect($fooMapResponseGet->getBody()->getContents())
            ->toBe('map-foo');

        $mapInvalidVerbException = null;

        try {
            $app->handle($testSupport->createRequest(
                '/foo-map',
                'PUT',
            ));
        } catch (Throwable $e) {
            $mapInvalidVerbException = $e;
        }

        expect($mapInvalidVerbException)
            ->toBeInstanceOf(HttpMethodNotAllowedException::class);

        // Group
        $fooGroupResponse = $app->handle($testSupport->createRequest(
            '/foo-group/foo-group-route-test',
            'GET',
        ));
        expect($fooGroupResponse->getBody()->getContents())
            ->toBe('group-route-test-foo');

        // Redirect
        $fooRedirectResponse = $app->handle($testSupport->createRequest(
            '/foo-redirect',
            'GET',
        ));
        expect($fooRedirectResponse->getHeader('Location')[0])
            ->toBe('/redirect-foo')
            ->and($fooRedirectResponse->getStatusCode())
            ->toBe(123);

        // Invalid route
        $notFoundExceptionInvalidRoute = null;

        try {
            $app->handle($testSupport->createRequest(
                '/foo-bar/bad-route',
                'GET',
            ));
        } catch (Throwable $e) {
            $notFoundExceptionInvalidRoute = $e;
        }

        expect($notFoundExceptionInvalidRoute)
            ->toBeInstanceOf(HttpNotFoundException::class);

        $invalidVerbException = null;

        try {
            $app->handle($testSupport->createRequest(
                '/foo-get',
                'POST',
            ));
        } catch (Throwable $e) {
            $invalidVerbException = $e;
        }

        expect($invalidVerbException)
            ->toBeInstanceOf(HttpMethodNotAllowedException::class);

        $app->setBasePath('/foo/bar');
        expect($applyRoutesEvent->getBasePath())
            ->toBe('/foo/bar');
    },
);

test(
    'Apply routes event getContainer method is available',
    function (): void {
        $container = BuildContainer::build();

        $app = AppFactory::create(container: $container);

        $applyRoutesEvent = new ApplyRoutesEvent($app);

        expect($applyRoutesEvent->getContainer())->toBe($container);
    },
);
