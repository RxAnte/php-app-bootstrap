<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\TestSupport;

use Crell\Tukio\OrderedProviderInterface;
use RxAnte\AppBootstrap\Boot;
use RxAnte\AppBootstrap\BootConfig;
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;
use RxAnte\AppBootstrap\Dependencies\Bindings;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function expect;

readonly class RunCliApp
{
    public function run(
        BootConfig $bootConfig,
        InputInterface $input,
        OutputInterface $output,
    ): void {
        $setContainerBindings = null;

        $setEventProvider = null;

        $setApplyCommandsEvent = null;

        (new Boot())
        ->start($bootConfig)
        ->buildContainer(static function (
            Bindings $bindings,
        ) use (&$setContainerBindings): void {
            $setContainerBindings = $bindings;
        })
        ->registerEventSubscribers(static function (
            OrderedProviderInterface $eventProvider,
        ) use (
            &$setEventProvider,
            &$setApplyCommandsEvent,
        ): void {
            $setEventProvider = $eventProvider;

            $eventProvider->listener(
                static function (ApplyCliCommandsEvent $event) use (
                    &$setApplyCommandsEvent,
                ): void {
                    $setApplyCommandsEvent = $event;

                    $event->addCommand(
                        'foo:bar',
                        'baz',
                    );
                },
            );
        })
        ->buildCliApplication()
        ->applyCommands()
        ->runApplication($input, $output);

        expect($setContainerBindings)
            ->toBeInstanceOf(Bindings::class)
            ->and($setEventProvider)
            ->toBeInstanceOf(OrderedProviderInterface::class)
            ->and($setApplyCommandsEvent)
            ->toBeInstanceOf(ApplyCliCommandsEvent::class);
    }
}
