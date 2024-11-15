<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Psr\EventDispatcher\EventDispatcherInterface;
use Silly\Application;

readonly class BootCommands
{
    public function __construct(
        private Application $app,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function applyCommands(): Run
    {
        $this->eventDispatcher->dispatch(new ApplyCliCommandsEvent(
            $this->app,
        ));

        return new Run($this->app);
    }
}
