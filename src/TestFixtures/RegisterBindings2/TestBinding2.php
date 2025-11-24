<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\TestFixtures\RegisterBindings2;

use RxAnte\AppBootstrap\Dependencies\Bindings;
use RxAnte\AppBootstrap\TestFixtures\TestService2;

readonly class TestBinding2
{
    public function __invoke(Bindings $bindings): void
    {
        $bindings->addBinding(
            TestService2::class,
            static fn () => new TestService2(
                'foo-test-string-2',
            ),
        );
    }
}
