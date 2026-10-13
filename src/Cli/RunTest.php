<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Mockery;
use Silly\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function expect;
use function test;
use function uses;

uses()->group('AppBootstrap', 'CliRun');

test(
    'Run::runApplication disables auto exit by default and returns the exit code',
    function (): void {
        // Arrange
        $app = Mockery::mock(Application::class);

        $app->expects('setAutoExit')->with(false);

        $app->expects('run')->andReturn(3);

        $run = new Run($app);

        // Act
        $exitCode = $run->runApplication();

        // Assert
        expect($exitCode)->toBe(3);
    },
);

test(
    'Run::runApplication enables auto exit when exitWithStatusCode is true',
    function (): void {
        // Arrange
        $app = Mockery::mock(Application::class);

        $app->expects('setAutoExit')->with(true);

        $app->expects('run')->andReturn(0);

        $run = new Run($app);

        // Act
        $exitCode = $run->runApplication(exitWithStatusCode: true);

        // Assert
        expect($exitCode)->toBe(0);
    },
);

test(
    'Run::runApplication passes input and output to the application',
    function (): void {
        // Arrange
        $input = new ArrayInput([]);

        $output = new BufferedOutput();

        $app = Mockery::mock(Application::class);

        $app->allows('setAutoExit');

        $app->expects('run')->with($input, $output)->andReturn(0);

        $run = new Run($app);

        // Act
        $exitCode = $run->runApplication($input, $output);

        // Assert
        expect($exitCode)->toBe(0);
    },
);
