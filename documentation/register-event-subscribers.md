# Register Events

The RxAnte bootstrap includes [crell/tukio](https://github.com/Crell/Tukio), which is a [PSR-14](https://www.php-fig.org/psr/psr-14/) event dispatcher.

In order to make use of events, your application will need to register event subscribers. In fact, in order to make use of features in this bootstrap, you will need to set up event subscribers for the [ApplyCliCommandsEvent](apply-cli-commands-event.md), [ApplyMiddlewareEvent](apply-middleware-event.md), and [ApplyRoutesEvent](apply-routes-event.md),

You can certainly also create and dispatch your own events and add listeners for those as well — or make use of other packages which create and dispatch events.

If you need to dispatch an event, request the interface `\Psr\EventDispatcher\EventDispatcherInterface` in the constructor of your class.

For more on subscribing to events from this bootstrap package, click through above to each of those.

Example of setting up event subscribers:

```php
<?php

declare(strict_types=1);

use Config\Dependencies\RegisterBindings;
use Config\Events\RegisterEventSubscribers;
use RxAnte\AppBootstrap\Boot;
use RxAnte\AppBootstrap\BootConfig;
use RxAnte\AppBootstrap\Dependencies\BuildContainerConfiguration;
use RxAnte\AppBootstrap\Http\BootHttpMiddlewareConfig;

require __DIR__ . '/vendor/autoload.php';

(new Boot())
    ->start()
    ->buildContainer()
    ->registerEventSubscribers([RegisterEventSubscribers::class, 'register'])
    // ...etc.
```

`RegisterEventSubscribers` above is a class with a static method of `register` (although the argument can be any callable). The method receives one argument of `\Crell\Tukio\OrderedProviderInterface`, which you can use to add subscribers.

There are several methods on that interface, but the one we use pretty much exclusively at RxAnte is the `addSubscriber` method, which receives a classname string. The class will be loaded from the container.

The class provided must have a public instance method that starts with `on` (such as `onApplyMiddleware`), and the argument must be typed as the dispatched event you wish to listen for.

Example:

```php
use Crell\Tukio\OrderedProviderInterface;

readonly class FooBarSomeSubscriber
{
    public function onEventDispatch(SomeEventClass $event): void
    {
        // ...do thing with $event
    }
}

readonly class EventSubscriberRegistration
{
    public static function register(OrderedProviderInterface $provider): void
    {
        $provider->addSubscriber(FooBarSomeSubscriber::class);
    }
}
```
