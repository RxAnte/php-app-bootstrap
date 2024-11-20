<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Mockery;
use RxAnte\AppBootstrap\Cli\TestSupport\SymfonyCommand;
use RxAnte\AppBootstrap\Dependencies\BuildContainer;
use Silly\Application;

use function test;
use function uses;

uses()->group('AppBootstrap', 'ApplyCliCommandsEvent');

test(
    'ApplyCliCommandsEvent::addSymfonyCommand accepts command',
    function (): void {
        // $container = Mockery::mock(ContainerInterface::class);

        $command = new SymfonyCommand();

        $app = Mockery::mock(Application::class);

        $app->expects('add')->with($command)
            ->andReturn($command);

        $event = new ApplyCliCommandsEvent($app);

        $event->addSymfonyCommand($command);
    },
);

test(
    'ApplyCliCommandsEvent::addSymfonyCommand accepts class string command',
    function (): void {
        $container = BuildContainer::build();

        $app = Mockery::mock(Application::class);

        $app->expects('getContainer')->andReturn($container);

        $app->expects('add')->with(SymfonyCommand::class)
            ->andReturn(new SymfonyCommand());

        $event = new ApplyCliCommandsEvent($app);

        $event->addSymfonyCommand(SymfonyCommand::class);
    },
);
