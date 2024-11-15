<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Silly\Application;
use Silly\Command\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification

readonly class ApplyCliCommandsEvent
{
    public function __construct(private readonly Application $app)
    {
    }

    /**
     * Define a CLI command using a string expression and a callable.
     *
     * @param string                $expression Defines the arguments and
     *                                          options of the command
     * @param callable|string|array $callable   Called when the command is
     *                                          called. This can be a
     *                                          "pseudo-callable" retrievable
     *                                          from the container
     * @param array                 $aliases    An array of aliases for command
     *
     * @phpstan-ignore-next-line
     */
    public function addCommand(
        string $expression,
        callable|string|array $callable,
        array $aliases = [],
    ): Command {
        return $this->app->command(
            $expression,
            $callable,
            $aliases,
        );
    }

    /**
     * @param Command|class-string<SymfonyCommand> $command
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function addSymfonyCommand(Command|string $command): SymfonyCommand
    {
        if (is_string($command)) {
            /** @phpstan-ignore-next-line */
            $command = $this->app->getContainer()->get($command);
        }

        /** @phpstan-ignore-next-line */
        return $this->app->add($command);
    }
}
