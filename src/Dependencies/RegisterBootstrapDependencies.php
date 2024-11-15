<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use Crell\Tukio\Dispatcher;
use Crell\Tukio\OrderedListenerProvider;
use Crell\Tukio\OrderedProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

readonly class RegisterBootstrapDependencies
{
    public static function register(ContainerBindings $containerBindings): void
    {
        $containerBindings->addBinding(
            EventDispatcherInterface::class,
            $containerBindings->resolveFromContainer(Dispatcher::class),
        );

        $containerBindings->addBinding(
            ListenerProviderInterface::class,
            $containerBindings->resolveFromContainer(
                OrderedListenerProvider::class,
            ),
        );

        $containerBindings->addBinding(
            OrderedProviderInterface::class,
            $containerBindings->resolveFromContainer(
                OrderedListenerProvider::class,
            ),
        );

        $containerBindings->addBinding(
            OrderedListenerProvider::class,
            $containerBindings->autowire()->constructorParameter(
                'container',
                $containerBindings->resolveFromContainer(
                    ContainerInterface::class,
                ),
            ),
        );
    }
}
