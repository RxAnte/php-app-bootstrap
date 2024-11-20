<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use RxAnte\AppBootstrap\Http\TestSupport\ApplyRoutesTestSupport;

use function expect;
use function test;
use function uses;

uses()->group('IsJsonRequest');

test(
    'IsJsonRequest::checkRequest returns true when is json request',
    function (): void {
        $request = (new ApplyRoutesTestSupport())->createRequest(
            '/foo',
            'GET',
        )->withHeader('Accept', 'application/json');

        expect(
            (new IsJsonRequest())->checkRequest($request),
        )->toBeTrue();
    },
);

test(
    'IsJsonRequest::checkRequest returns false when is text/html request',
    function (): void {
        $request = (new ApplyRoutesTestSupport())->createRequest(
            '/foo',
            'GET',
        )->withHeader('Accept', 'text/html');

        expect(
            (new IsJsonRequest())->checkRequest($request),
        )->toBeFalse();
    },
);

test(
    'IsJsonRequest::checkRequest returns false when is application/xml request',
    function (): void {
        $request = (new ApplyRoutesTestSupport())->createRequest(
            '/foo',
            'GET',
        )->withHeader('Accept', 'application/xml');

        expect(
            (new IsJsonRequest())->checkRequest($request),
        )->toBeFalse();
    },
);

test(
    'IsJsonRequest::checkRequest returns false when no accept header',
    function (): void {
        $request = (new ApplyRoutesTestSupport())->createRequest(
            '/foo',
            'GET',
        );

        expect(
            (new IsJsonRequest())->checkRequest($request),
        )->toBeFalse();
    },
);
