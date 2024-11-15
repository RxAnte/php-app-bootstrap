<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

readonly class BuildContainer
{
    public static function build(
        callable|null $register = null,
        BuildContainerConfiguration $config = new BuildContainerConfiguration(),
    ): ContainerInterface {
        $containerBindings = new ContainerBindings();

        /**
         * Register local bindings that AppBootstrap needs. Though technically
         * right now we could register them via the callable, the idea is to
         * move this AppBootstrap to its own package, and at that point this
         * package would need to register its own dependencies first
         */
        RegisterBootstrapDependencies::register(
            $containerBindings,
        );

        if ($register !== null) {
            $register($containerBindings);
        }

        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->addDefinitions($containerBindings->bindings());

        if ($config->enableCompilationToDir !== null) {
            $builder = $builder->enableCompilation(
                $config->enableCompilationToDir,
            );
        }

        if ($config->writeProxiesToDir !== null) {
            $builder = $builder->writeProxiesToFile(
                true,
                $config->writeProxiesToDir,
            );
        }

        return $builder->build();
    }
}
