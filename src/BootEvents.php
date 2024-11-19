<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Crell\Tukio\OrderedProviderInterface;

readonly class BootEvents
{
    public function __construct(
        private BootApplication $bootApplication,
        private OrderedProviderInterface $eventProvider,
    ) {
    }

    public function registerEventSubscribers(
        callable|null $register = null,
    ): BootApplication {
        if ($register !== null) {
            $register($this->eventProvider);
        }

        return $this->bootApplication;
    }
}
