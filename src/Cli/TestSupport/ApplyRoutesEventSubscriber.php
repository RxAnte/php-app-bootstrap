<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli\TestSupport;

use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;

readonly class ApplyRoutesEventSubscriber
{
    public function onDispatch(ApplyRoutesEvent $routes): void
    {
        $routes->any('/custom', static function (): void {
        });

        $routes->get('/one', InvokableOne::class);

        $routes->get('/two', InvokableTwo::class);
    }
}
