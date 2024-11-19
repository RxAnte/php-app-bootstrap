<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use RxAnte\AppBootstrap\TestSupport\RunCliApp;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\Output;

use function expect;
use function restore_error_handler;
use function restore_exception_handler;
use function test;
use function uses;

uses()->group('Bootstrap', 'BootstrapCli');

$input = new ArrayInput([]);

$output = new class () extends Output {
    public string $message = '';

    protected function doWrite(string $message, bool $newline): void
    {
        $this->message .= $message;
    }
};

test(
    'CLI boots without whoops error handling',
    function () use ($input, $output): void {
        $output->message = '';

        (new RunCliApp())->run(
            new BootConfig(
                isCli: true,
                useWhoopsErrorHandling: false,
            ),
            $input,
            $output,
        );

        expect($output->message)->toBeString()->not->toBeEmpty();
    },
);

test(
    'CLI boots with whoops error handling',
    function () use ($input, $output): void {
        $output->message = '';

        (new RunCliApp())->run(
            new BootConfig(
                isCli: true,
                useWhoopsErrorHandling: true,
            ),
            $input,
            $output,
        );

        expect($output->message)
            ->toBeString()
            ->toContain('foo:bar')
            ->not
            ->toBeEmpty();

        restore_error_handler();
        restore_exception_handler();
    },
);
