<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\TestFixtures\RegisterBindings;

use RxAnte\AppBootstrap\Dependencies\Bindings;
use RxAnte\AppBootstrap\TestFixtures\TestService;

readonly class TestBinding
{
    public function __invoke(Bindings $bindings): void
    {
        $bindings->addBinding(
            TestService::class,
            static fn () => new TestService(
                'foo-test-string',
            ),
        );
    }
}
