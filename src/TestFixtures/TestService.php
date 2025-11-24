<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\TestFixtures;

readonly class TestService
{
    public function __construct(public string $testConstructorStringVal)
    {
    }
}
