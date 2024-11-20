<?php

declare(strict_types=1);

namespace RxAnte\Tests;

readonly class ConsoleOutputLine
{
    public function __construct(
        public string $message,
        public bool $newLine,
    ) {
    }
}
