<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use Crell\Tukio\OrderedListenerProvider;
use Crell\Tukio\OrderedProviderInterface;
use DI\ContainerBuilder;
use stdClass;

use function expect;
use function test;
use function uses;

uses()->group('AppBootstrap', 'ContainerBindings');

test(
    'Container bindings can be added',
    function (): void {
        $bindings = new Bindings();

        $test1       = new stdClass();
        $test1->name = 'test1-name';
        $bindings->addBinding('test1', $test1);

        $bindings->addBindings([
            'test2' => static fn () => 'test2-string',
            'test3' => static fn () => 'test3-string',
        ]);

        $bindings->addBinding(
            OrderedProviderInterface::class,
            $bindings->autowire(OrderedListenerProvider::class),
        );

        $bindings->addBinding(
            'test4',
            $bindings->resolveFromContainer('test1'),
        );

        $container = (new ContainerBuilder())
            ->useAutowiring(true)
            ->addDefinitions($bindings->getBindings())
            ->build();

        /** @phpstan-ignore-next-line */
        expect($container->get('test1')->name)
            ->toBe('test1-name')
            ->and($container->get('test2'))
            ->toBe('test2-string')
            ->and($container->get('test3'))
            ->toBe('test3-string')
            ->and($container->get(OrderedProviderInterface::class))
            ->toBeInstanceOf(OrderedListenerProvider::class)
            /** @phpstan-ignore-next-line */
            ->and($container->get('test4')->name)
            ->toBe('test1-name');
    },
);
