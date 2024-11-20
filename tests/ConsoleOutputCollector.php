<?php

declare(strict_types=1);

namespace RxAnte\Tests;

use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputCollector extends ConsoleOutput
{
    /** @var ConsoleOutputLine[] */
    public array $lines = [];

    protected function doWrite(string $message, bool $newline): void
    {
        $this->lines[] = new ConsoleOutputLine(
            $message,
            $newline,
        );
    }
}
