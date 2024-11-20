<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Crell\Tukio\OrderedProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use RxAnte\AppBootstrap\Dependencies\BuildContainer;
use RxAnte\AppBootstrap\Http\TestSupport\ApplyRoutesTestSupport;
use RxAnte\AppBootstrap\Http\TestSupport\RequestResponseCallableCustomArguments;
use RxAnte\AppBootstrap\Http\TestSupport\RequestResponseCallableDefaultArguments;
use RxAnte\AppBootstrap\Http\TestSupport\RequestResponseCustomEventSubscriber;

use function assert;
use function expect;
use function test;
use function uses;

uses()->group('Bootstrap', 'RequestResponseCustom');

test(
    'RequestResponseCustom runs with default arguments',
    function (): void {
        $container = BuildContainer::build();

        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        assert($eventDispatcher instanceof EventDispatcherInterface);

        $request = (new ApplyRoutesTestSupport())->createRequest(
            '/foo',
            'GET',
        );

        $responseFactory = $container->get(ResponseFactoryInterface::class);
        assert($responseFactory instanceof ResponseFactoryInterface);

        $response = $responseFactory->createResponse();

        $requestResponseCustom = new RequestResponseCustom(
            $eventDispatcher,
        );

        $returnedResponse = $requestResponseCustom->__invoke(
            new RequestResponseCallableDefaultArguments(),
            $request,
            $response,
            [
                'foo' => 'bar',
                'baz' => 'bap',
            ],
        );

        expect((string) $returnedResponse->getBody())->toBe(
            'RequestResponseCallableDefaultArguments',
        );

        expect($returnedResponse->getStatusCode())->toBe(501);
    },
);

test(
    'RequestResponseCustom runs with arguments from event dispatch',
    function (): void {
        $container = BuildContainer::build();

        $eventProvider = $container->get(OrderedProviderInterface::class);
        assert($eventProvider instanceof OrderedProviderInterface);

        $eventProvider->addSubscriber(
            RequestResponseCustomEventSubscriber::class,
        );

        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        assert($eventDispatcher instanceof EventDispatcherInterface);

        $request = (new ApplyRoutesTestSupport())->createRequest(
            '/foo',
            'GET',
        );

        $responseFactory = $container->get(ResponseFactoryInterface::class);
        assert($responseFactory instanceof ResponseFactoryInterface);

        $response = $responseFactory->createResponse();

        $requestResponseCustom = new RequestResponseCustom(
            $eventDispatcher,
        );

        $returnedResponse = $requestResponseCustom->__invoke(
            new RequestResponseCallableCustomArguments(),
            $request,
            $response,
            ['foo' => 'bar'],
        );

        expect((string) $returnedResponse->getBody())->toBe(
            'bar',
        );

        expect($returnedResponse->getStatusCode())->toBe(301);
    },
);
