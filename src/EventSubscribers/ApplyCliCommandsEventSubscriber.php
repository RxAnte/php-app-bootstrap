<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\EventSubscribers;

use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;
use RxAnte\AppBootstrap\Cli\ConfigListRoutesCommand;

readonly class ApplyCliCommandsEventSubscriber
{
    public function onApplyCommands(ApplyCliCommandsEvent $commands): void
    {
        ConfigListRoutesCommand::applyCommand($commands);
    }
}
