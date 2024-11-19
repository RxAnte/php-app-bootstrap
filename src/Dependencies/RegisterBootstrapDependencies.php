<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use Crell\Tukio\Dispatcher;
use Crell\Tukio\OrderedListenerProvider;
use Crell\Tukio\OrderedProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use RxAnte\AppBootstrap\Http\RequestResponseCustom;
use Slim\CallableResolver;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Interfaces\AdvancedCallableResolverInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteCollector;

readonly class RegisterBootstrapDependencies
{
    public static function register(Bindings $bindings): void
    {
        self::registerSlimBindings($bindings);
        self::registerEventBindings($bindings);
    }

    private static function registerSlimBindings(
        Bindings $bindings,
    ): void {
        $bindings->addBinding(
            ServerRequestInterface::class,
            static function () {
                return ServerRequestCreatorFactory::create()
                    ->createServerRequestFromGlobals();
            },
        );

        $bindings->addBinding(
            ResponseFactoryInterface::class,
            $bindings->resolveFromContainer(ResponseFactory::class),
        );

        $bindings->addBinding(
            RouteCollector::class,
            $bindings->autowire(RouteCollector::class)
                ->constructorParameter(
                    'defaultInvocationStrategy',
                    $bindings->resolveFromContainer(
                        RequestResponseCustom::class,
                    ),
                ),
        );

        $bindings->addBinding(
            CallableResolver::class,
            $bindings->autowire(CallableResolver::class)
                ->constructorParameter(
                    'container',
                    $bindings->resolveFromContainer(
                        ContainerInterface::class,
                    ),
                ),
        );

        $bindings->addBinding(
            CallableResolverInterface::class,
            $bindings->resolveFromContainer(
                CallableResolver::class,
            ),
        );

        $bindings->addBinding(
            AdvancedCallableResolverInterface::class,
            $bindings->resolveFromContainer(
                CallableResolver::class,
            ),
        );

        $bindings->addBinding(
            RouteCollectorInterface::class,
            $bindings->resolveFromContainer(
                RouteCollector::class,
            ),
        );
    }

    private static function registerEventBindings(
        Bindings $bindings,
    ): void {
        $bindings->addBinding(
            EventDispatcherInterface::class,
            $bindings->resolveFromContainer(Dispatcher::class),
        );

        $bindings->addBinding(
            ListenerProviderInterface::class,
            $bindings->resolveFromContainer(
                OrderedListenerProvider::class,
            ),
        );

        $bindings->addBinding(
            OrderedProviderInterface::class,
            $bindings->resolveFromContainer(
                OrderedListenerProvider::class,
            ),
        );

        $bindings->addBinding(
            OrderedListenerProvider::class,
            $bindings->autowire()->constructorParameter(
                'container',
                $bindings->resolveFromContainer(
                    ContainerInterface::class,
                ),
            ),
        );
    }
}
