<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Crell\Tukio\OrderedProviderInterface;
use Mockery;
use RxAnte\AppBootstrap\Dependencies\RegisterEventSubscribersInDirectories;
use RxAnte\AppBootstrap\EventSubscribers\ApplyCliCommandsEventSubscriber;
use RxAnte\AppBootstrap\EventSubscribers\EventSubscribersDirectory;
use RxAnte\AppBootstrap\TestFixtures\EventSubscribers\TestEventSubscriber;
use RxAnte\AppBootstrap\TestFixtures\EventSubscribers\TestEventSubscriber2;
use RxAnte\AppBootstrap\TestFixtures\EventSubscribers2\TestEventSubscriber3;

use function expect;
use function it;
use function uses;

uses()->group('Bootstrap', 'BootEvents');

// phpcs:disable Squiz.Classes.ClassFileName.NoMatch

class BootEventsTestSubscriberStorage
{
    /** @var string[] */
    public array $subscribers = [];
}

it(
    'registers event subscribers with no register parameter',
    function (): void {
        $bootApplication = Mockery::mock(BootApplication::class);

        $eventProvider = Mockery::mock(OrderedProviderInterface::class);

        $subStorage = new BootEventsTestSubscriberStorage();

        $eventProvider->allows('addSubscriber')->andReturnUsing(
            function (string $subscriber) use ($subStorage): void {
                $subStorage->subscribers[] = $subscriber;
            },
        );

        $bootEvents = new BootEvents(
            bootApplication: $bootApplication,
            eventProvider: $eventProvider,
            registerEventSubscribersInDirectories: new RegisterEventSubscribersInDirectories(
                $eventProvider,
            ),
        );

        $return = $bootEvents->registerEventSubscribers();

        expect($return)->toBe($bootApplication);

        expect($subStorage->subscribers)->toBe([
            EventSubscribersDirectory::class,
            ApplyCliCommandsEventSubscriber::class,
        ]);
    },
);

it(
    'registers event subscribers with callable register',
    function (): void {
        $bootApplication = Mockery::mock(BootApplication::class);

        $eventProvider = Mockery::mock(OrderedProviderInterface::class);

        $subStorage = new BootEventsTestSubscriberStorage();

        $eventProvider->allows('addSubscriber')->andReturnUsing(
            function (string $subscriber) use ($subStorage): void {
                $subStorage->subscribers[] = $subscriber;
            },
        );

        $bootEvents = new BootEvents(
            bootApplication: $bootApplication,
            eventProvider: $eventProvider,
            registerEventSubscribersInDirectories: new RegisterEventSubscribersInDirectories(
                $eventProvider,
            ),
        );

        $return = $bootEvents->registerEventSubscribers(
            register: function (OrderedProviderInterface $provider): void {
                $provider->addSubscriber(TestEventSubscriber::class);
            },
        );

        expect($return)->toBe($bootApplication);

        expect($subStorage->subscribers)->toBe([
            TestEventSubscriber::class,
            EventSubscribersDirectory::class,
            ApplyCliCommandsEventSubscriber::class,
        ]);
    },
);

it(
    'registers event subscribers with string directory path',
    function (): void {
        $bootApplication = Mockery::mock(BootApplication::class);

        $eventProvider = Mockery::mock(OrderedProviderInterface::class);

        $subStorage = new BootEventsTestSubscriberStorage();

        $eventProvider->allows('addSubscriber')->andReturnUsing(
            function (string $subscriber) use ($subStorage): void {
                $subStorage->subscribers[] = $subscriber;
            },
        );

        $bootEvents = new BootEvents(
            bootApplication: $bootApplication,
            eventProvider: $eventProvider,
            registerEventSubscribersInDirectories: new RegisterEventSubscribersInDirectories(
                $eventProvider,
            ),
        );

        $return = $bootEvents->registerEventSubscribers(
            register: __DIR__ . '/TestFixtures/EventSubscribers',
        );

        expect($return)->toBe($bootApplication);

        expect($subStorage->subscribers)->toBe([
            TestEventSubscriber::class,
            TestEventSubscriber2::class,
            EventSubscribersDirectory::class,
            ApplyCliCommandsEventSubscriber::class,
        ]);
    },
);

it(
    'registers event subscribers with array of directory paths',
    function (): void {
        $bootApplication = Mockery::mock(BootApplication::class);

        $eventProvider = Mockery::mock(OrderedProviderInterface::class);

        $subStorage = new BootEventsTestSubscriberStorage();

        $eventProvider->allows('addSubscriber')->andReturnUsing(
            function (string $subscriber) use ($subStorage): void {
                $subStorage->subscribers[] = $subscriber;
            },
        );

        $bootEvents = new BootEvents(
            bootApplication: $bootApplication,
            eventProvider: $eventProvider,
            registerEventSubscribersInDirectories: new RegisterEventSubscribersInDirectories(
                $eventProvider,
            ),
        );

        $return = $bootEvents->registerEventSubscribers(
            register: [
                __DIR__ . '/TestFixtures/EventSubscribers',
                __DIR__ . '/TestFixtures/EventSubscribers2',
            ],
        );

        expect($return)->toBe($bootApplication);

        expect($subStorage->subscribers)->toBe([
            TestEventSubscriber::class,
            TestEventSubscriber2::class,
            TestEventSubscriber3::class,
            EventSubscribersDirectory::class,
            ApplyCliCommandsEventSubscriber::class,
        ]);
    },
);
