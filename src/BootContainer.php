<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use RxAnte\AppBootstrap\Dependencies\BuildContainer;
use RxAnte\AppBootstrap\Dependencies\BuildContainerConfiguration;

use function assert;

readonly class BootContainer
{
    public function buildContainer(
        callable|null $register = null,
        BuildContainerConfiguration $config = new BuildContainerConfiguration(),
    ): BootEvents {
        $container = BuildContainer::build($register);

        $bootEvents = $container->get(BootEvents::class);

        assert($bootEvents instanceof BootEvents);

        return $bootEvents;
    }
}
