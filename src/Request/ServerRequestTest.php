<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Request;

use Mockery;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

use function expect;
use function it;
use function uses;

uses()->group('Bootstrap', 'ServerRequest');

it(
    'delegates methods and creates typed attributes',
    function (): void {
        $uri = new Uri(
            'https',
            'foo-bar.com',
            567,
            '/baz/bar',
            'foo=bar&baz=foo',
            'frag',
            'jane',
            'pass',
        );

        $headers = new Headers([
            'header1' => 'headerValue1',
            'header2' => 'headerValue2',
        ]);

        $body = Mockery::mock(StreamInterface::class);

        $body->expects('__toString')->andReturn('foo-bar-body');

        $request = (new Request(
            'GET',
            $uri,
            $headers,
            [
                'cookie1' => 'cookieValue1',
                'cookie2' => 'cookieValue2',
            ],
            [
                'serverParam1' => 'serverValue1',
                'serverParam2' => 'serverValue2',
            ],
            $body,
            [
                'uploadedFile1' => 'uploadedValue1',
                'uploadedFile2' => 'uploadedValue2',
            ],
        ))->withParsedBody([
            'body1' => 'bodyVal1',
            'body2' => 'bodyVal2',
        ])->withAttribute('fooAttr', 'barAttr');

        $sut = new ServerRequest($request);

        expect($sut->serverParams->attributes)->toBe([
            'serverParam1' => 'serverValue1',
            'serverParam2' => 'serverValue2',
        ]);

        expect($sut->cookieParams->attributes)->toBe([
            'cookie1' => 'cookieValue1',
            'cookie2' => 'cookieValue2',
        ]);

        expect($sut->queryParams->attributes)->toBe([
            'foo' => 'bar',
            'baz' => 'foo',
        ]);

        expect($sut->parsedBody->attributes)->toBe([
            'body1' => 'bodyVal1',
            'body2' => 'bodyVal2',
        ]);

        expect($sut->getProtocolVersion())->toBe('1.1');

        expect($sut->getHeaders())->toBe([
            'header1' => ['headerValue1'],
            'header2' => ['headerValue2'],
            'Host' => ['foo-bar.com'],
        ]);

        expect($sut->hasHeader('asdf'))->toBeFalse();

        expect($sut->hasHeader('header1'))->toBeTrue();

        expect($sut->getHeader('header1'))->toBe(
            ['headerValue1'],
        );

        expect($sut->getHeaderLine('header2'))->toBe(
            'headerValue2',
        );

        expect($sut->getBody()->__toString())->toBe(
            'foo-bar-body',
        );

        expect($sut->getRequestTarget())->toBe(
            '/baz/bar?foo=bar&baz=foo',
        );

        expect($sut->getMethod())->toBe('GET');

        expect($sut->getUri())->toBe($uri);

        expect($sut->getServerParams())->toBe([
            'serverParam1' => 'serverValue1',
            'serverParam2' => 'serverValue2',
        ]);

        expect($sut->getCookieParams())->toBe([
            'cookie1' => 'cookieValue1',
            'cookie2' => 'cookieValue2',
        ]);

        expect($sut->getQueryParams())->toBe([
            'foo' => 'bar',
            'baz' => 'foo',
        ]);

        expect($sut->getUploadedFiles())->toBe([
            'uploadedFile1' => 'uploadedValue1',
            'uploadedFile2' => 'uploadedValue2',
        ]);

        expect($sut->getParsedBody())->toBe([
            'body1' => 'bodyVal1',
            'body2' => 'bodyVal2',
        ]);

        expect($sut->getAttributes())->toBe(
            ['fooAttr' => 'barAttr'],
        );

        expect($sut->getAttribute('fooAttr'))->toBe(
            'barAttr',
        );

        expect($sut->getAttribute('asdf'))->toBeNull();

        expect(
            $sut->withProtocolVersion('1.0')
                ->getProtocolVersion(),
        )->toBe('1.0');

        expect(
            $sut->withHeader('asdfHeader', 'asdfValue')
                ->getHeader('asdfHeader'),
        )->toBe(['asdfValue']);

        expect(
            $sut->withAddedHeader('header2', 'foo-bar-val')
                    ->getHeader('header2'),
        )->toBe(['headerValue2', 'foo-bar-val']);

        expect(
            $sut->withoutHeader('header2')
                ->getHeader('header2'),
        )->toBe([]);

        $newBody = Mockery::mock(StreamInterface::class);

        expect($sut->withBody($newBody)->getBody())->toBe(
            $newBody,
        );

        expect(
            $sut->withRequestTarget('/foo/asdf')
                ->getRequestTarget(),
        )->toBe('/foo/asdf');

        expect($sut->withMethod('POST')->getMethod())->toBe(
            'POST',
        );

        $newUri = new Uri(
            'http',
            'bar-foo.dev',
            567,
            '/asdf',
        );

        expect($sut->withUri($newUri)->getUri())->toBe(
            $newUri,
        );

        expect(
            $sut->withCookieParams(
                ['cookie' => 'val'],
            )->getCookieParams(),
        )->toBe(['cookie' => 'val']);

        expect(
            $sut->withQueryParams(
                ['query' => 'foo'],
            )->getQueryParams(),
        )->toBe(['query' => 'foo']);

        expect(
            $sut->withUploadedFiles(
                ['file' => 'thing'],
            )->getUploadedFiles(),
        )->toBe(['file' => 'thing']);

        expect(
            $sut->withParsedBody(
                ['body' => 'value'],
            )->getParsedBody(),
        )->toBe(['body' => 'value']);

        expect(
            $sut->withAttribute('foo', 'val')
                ->getAttribute('foo'),
        )->toBe('val');

        expect(
            $sut->withoutAttribute('fooAttr')->getAttributes(),
        )->toBe([]);
    },
);
