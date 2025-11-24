<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Psr\Container\ContainerInterface;
use ReflectionObject;
use RxAnte\AppBootstrap\Dependencies\Bindings;
use RxAnte\AppBootstrap\TestFixtures\TestService;
use RxAnte\AppBootstrap\TestFixtures\TestService2;

use function assert;
use function expect;
use function it;
use function uses;

uses()->group('Bootstrap', 'BootContainer');

it(
    'builds container with no register parameter',
    function (): void {
        $bootContainer = new BootContainer();

        $bootEvents = $bootContainer->buildContainer();

        expect($bootEvents)->toBeInstanceOf(BootEvents::class);
    },
);

it(
    'builds container with callable register',
    function (): void {
        $bootContainer = new BootContainer();

        $called = false;

        $register = function (Bindings $bindings) use (&$called): void {
            $called = true;
        };

        $bootEvents = $bootContainer->buildContainer($register);

        expect($bootEvents)->toBeInstanceOf(BootEvents::class)
            ->and($called)->toBeTrue();
    },
);

it(
    'builds container with string directory path',
    function (): void {
        $bootContainer = new BootContainer();

        $testDir = __DIR__ . '/TestFixtures/RegisterBindings';

        $bootEvents = $bootContainer->buildContainer($testDir);

        expect($bootEvents)->toBeInstanceOf(BootEvents::class);

        $bootApplication = $bootEvents->registerEventSubscribers();

        $bootAppRef  = new ReflectionObject($bootApplication);
        $propertyRef = $bootAppRef->getProperty('container');
        $propertyRef->setAccessible(true);

        $container = $propertyRef->getValue($bootApplication);

        expect($container)->toBeInstanceOf(
            ContainerInterface::class,
        );
        assert($container instanceof ContainerInterface);

        $testService = $container->get(TestService::class);

        expect($testService)->toBeInstanceOf(TestService::class);
        assert($testService instanceof TestService);

        expect($testService->testConstructorStringVal)->toBe(
            'foo-test-string',
        );
    },
);

it(
    'builds container with array of directory paths',
    function (): void {
        $bootContainer = new BootContainer();

        $testDirs = [
            __DIR__ . '/TestFixtures/RegisterBindings',
            __DIR__ . '/TestFixtures/RegisterBindings2',
        ];

        $bootEvents = $bootContainer->buildContainer($testDirs);

        expect($bootEvents)->toBeInstanceOf(BootEvents::class);

        expect($bootEvents)->toBeInstanceOf(BootEvents::class);

        $bootApplication = $bootEvents->registerEventSubscribers();

        $bootAppRef  = new ReflectionObject($bootApplication);
        $propertyRef = $bootAppRef->getProperty('container');
        $propertyRef->setAccessible(true);

        $container = $propertyRef->getValue($bootApplication);

        expect($container)->toBeInstanceOf(
            ContainerInterface::class,
        );
        assert($container instanceof ContainerInterface);

        $testService = $container->get(TestService::class);
        expect($testService)->toBeInstanceOf(TestService::class);
        assert($testService instanceof TestService);
        expect($testService->testConstructorStringVal)->toBe(
            'foo-test-string',
        );

        $testService2 = $container->get(TestService2::class);
        expect($testService2)->toBeInstanceOf(TestService2::class);
        assert($testService2 instanceof TestService2);
        expect($testService2->testConstructorStringVal)->toBe(
            'foo-test-string-2',
        );
    },
);
