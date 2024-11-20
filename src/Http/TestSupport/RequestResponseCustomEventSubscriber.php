<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http\TestSupport;

use RxAnte\AppBootstrap\Http\RequestResponseEvent;

use function array_merge;

readonly class RequestResponseCustomEventSubscriber
{
    public function onDispatch(RequestResponseEvent $event): void
    {
        $event->callableArguments = array_merge(
            [new CustomArgument('bar')],
            $event->callableArguments,
        );
    }
}
