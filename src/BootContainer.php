<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use RxAnte\AppBootstrap\Dependencies\BuildContainer;
use RxAnte\AppBootstrap\Dependencies\BuildContainerConfiguration;
use RxAnte\AppBootstrap\Dependencies\RegisterBindingsInDirectories;

use function assert;
use function is_array;
use function is_callable;
use function is_string;

readonly class BootContainer
{
    /** @param callable|string|array<string>|null $register */
    public function buildContainer(
        callable|string|array|null $register = null,
        BuildContainerConfiguration $config = new BuildContainerConfiguration(),
    ): BootEvents {
        $register = is_string($register) ? [$register] : $register;

        if (! is_callable($register) && is_array($register)) {
            $register = new RegisterBindingsInDirectories(
                $register,
            );
        }

        $container = BuildContainer::build(
            $register,
            $config,
        );

        $bootEvents = $container->get(BootEvents::class);

        assert($bootEvents instanceof BootEvents);

        return $bootEvents;
    }
}
