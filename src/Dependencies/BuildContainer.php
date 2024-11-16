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
        $containerBindings = new Bindings();

        RegisterBootstrapDependencies::register(
            $containerBindings,
        );

        if ($register !== null) {
            $register($containerBindings);
        }

        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->addDefinitions($containerBindings->getBindings());

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
