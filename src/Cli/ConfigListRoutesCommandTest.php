<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Crell\Tukio\OrderedProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use RxAnte\AppBootstrap\Cli\TestSupport\ApplyRoutesEventSubscriber;
use RxAnte\AppBootstrap\Dependencies\BuildContainer;
use RxAnte\Tests\ConsoleOutputCollector;

use function assert;
use function expect;
use function test;
use function uses;

uses()->group('ConfigListRoutesCommand');

test(
    'ConfigListRoutesCommand runs',
    function (): void {
        $container = BuildContainer::build();

        $eventProvider = $container->get(OrderedProviderInterface::class);
        assert($eventProvider instanceof OrderedProviderInterface);

        $eventProvider->addSubscriber(ApplyRoutesEventSubscriber::class);

        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        assert($eventDispatcher instanceof EventDispatcherInterface);

        $consoleOutput = $container->get(ConsoleOutputCollector::class);
        assert($consoleOutput instanceof ConsoleOutputCollector);

        $command = new ConfigListRoutesCommand(
            container: $container,
            eventDispatcher: $eventDispatcher,
            output: $consoleOutput,
        );

        $command->__invoke();

        expect($consoleOutput->lines)->toHaveCount(7);

        $line0 = $consoleOutput->lines[0];
        expect($line0->message)->toBe(
            '+----------------------------------------+---------+--------------------------------------------------+',
        )->and($line0->newLine)->toBeTrue();

        // Line 1 is proving difficult to test so we'll leave it for now

        $line2 = $consoleOutput->lines[2];
        expect($line2->message)->toBe(
            '+----------------------------------------+---------+--------------------------------------------------+',
        )->and($line2->newLine)->toBeTrue();

        $line3 = $consoleOutput->lines[3];
        expect($line3->message)->toBe(
            '| GET, POST, PUT, PATCH, DELETE, OPTIONS | /custom | anonymous function                               |',
        )->and($line3->newLine)->toBeTrue();

        $line4 = $consoleOutput->lines[4];
        expect($line4->message)->toBe(
            '| GET                                    | /one    | RxAnte\AppBootstrap\Cli\TestSupport\InvokableOne |',
        )->and($line4->newLine)->toBeTrue();

        $line5 = $consoleOutput->lines[5];
        expect($line5->message)->toBe(
            '| GET                                    | /two    | RxAnte\AppBootstrap\Cli\TestSupport\InvokableTwo |',
        )->and($line5->newLine)->toBeTrue();

        $line6 = $consoleOutput->lines[6];
        expect($line6->message)->toBe(
            '+----------------------------------------+---------+--------------------------------------------------+',
        )->and($line6->newLine)->toBeTrue();
    },
);
