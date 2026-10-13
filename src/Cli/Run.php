<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Silly\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

readonly class Run
{
    public function __construct(private Application $app)
    {
    }

    public function runApplication(
        InputInterface|null $input = null,
        OutputInterface|null $output = null,
        bool $exitWithStatusCode = false,
    ): int {
        $this->app->setAutoExit($exitWithStatusCode);

        return $this->app->run($input, $output);
    }
}
