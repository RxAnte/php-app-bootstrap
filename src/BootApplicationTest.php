<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Mockery;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use function assert;
use function expect;
use function test;
use function uses;

uses()->group('Bootstrap', 'BootApplication');

test(
    'Test BootApplication::getContainer returns container',
    function (): void {
        $container = Mockery::mock(ContainerInterface::class);
        assert($container instanceof ContainerInterface);

        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        assert($eventDispatcher instanceof EventDispatcherInterface);

        $bootApplication = new BootApplication(
            $container,
            $eventDispatcher,
        );

        expect($bootApplication->getContainer())
            ->toBe($container);
    },
);
