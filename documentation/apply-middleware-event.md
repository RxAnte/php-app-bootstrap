# Set Up HTTP Application Middleware

In order to set up middleware for the entire HTTP application, an event is dispatched: `\RxAnte\AppBootstrap\Http\ApplyMiddlewareEvent`. You'll need to subscribe to that event in order to set up middleware for your application.

```php
use RxAnte\AppBootstrap\Http\ApplyMiddlewareEvent;

readonly class ApplyMiddlewareSubscriber
{
    public function onApplyMiddleware(ApplyMiddlewareEvent $middleware): void
    {
        $middleware->add(MyMiddlwareInterfaceImplementingClass::class);
    }
}
```

## `add` method

The `add` method accepts an instance of `\Psr\Http\Server\MiddlewareInterface`, or a string classname of a class that implements `\Psr\Http\Server\MiddlewareInterface` (it will be retrieved from the container) (this is the best approach), or a callable that receives arguments and returns a response.
