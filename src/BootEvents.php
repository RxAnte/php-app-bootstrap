<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Crell\Tukio\OrderedProviderInterface;
use RxAnte\AppBootstrap\Dependencies\RegisterEventSubscribersInDirectories;
use RxAnte\AppBootstrap\EventSubscribers\EventSubscribersDirectory;

use function is_array;
use function is_callable;
use function is_string;

readonly class BootEvents
{
    public function __construct(
        private BootApplication $bootApplication,
        private OrderedProviderInterface $eventProvider,
        private RegisterEventSubscribersInDirectories $registerEventSubscribersInDirectories,
    ) {
    }

    /** @param callable|string|array<string>|null $register */
    public function registerEventSubscribers(
        callable|string|array|null $register = null,
    ): BootApplication {
        $register = is_string($register) ? [$register] : $register;

        if (is_callable($register)) {
            $register($this->eventProvider);

            $register = [];
        }

        if (! is_array($register)) {
            $register = [];
        }

        // Package event subscribers
        $register[] = EventSubscribersDirectory::PATH;

        $this->registerEventSubscribersInDirectories->register(
            $register,
        );

        return $this->bootApplication;
    }
}
