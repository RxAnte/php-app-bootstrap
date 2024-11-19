# Set UP HTTP Routes

In order to set up routes for the HTTP application, an event is dispatched: `\RxAnte\AppBootstrap\Http\ApplyRoutesEvent`. You'll need to subscribe to that event in order to set up routes for your application.

```php
use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;

readonly class ApplyRoutesSubscriber
{
    public function onApplyRoutes(ApplyRoutesEvent $routes): void
    {
        $routes->get('/my/route', MyInvokableClass::class);
    }
}
```

> [!NOTE]  
> Class strings are loaded from the container.

## Routing event methods

`RxAnte\AppBootstrap\Http\ApplyRoutesEvent` has several methods for routing.

### `get`

The `get` method sets up a route that accepts an HTTP GET request.

### `post`

The `post` method sets up a route that accepts an HTTP POST request.

### `put`

The `put` method sets up a route that accepts an HTTP PUT request.

### `patch`

The `patch` method sets up a route that accepts an HTTP PATCH request.

### `delete`

The `delete` method sets up a route that accepts an HTTP DELETE request.

### `options`

The `options` method sets up a route that accepts an HTTP OPTIONS request.

### `any`

The `any` method sets up a route that accepts any HTTP method request.

### `map`

The `map` method sets up a route that accepts any HTTP method listen in the first argument.

Example:

```php
use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;

readonly class ApplyRoutesSubscriber
{
    public function onApplyRoutes(ApplyRoutesEvent $routes): void
    {
        $routes->map(
            ['GET', 'POST'],
            '/my/route',
            MyInvokableClass::class,
        );
    }
}
```

### `group`

The `group` method sets up a group of routes that begin with a certain pattern.

Example:

```php
use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;
use Slim\Interfaces\RouteCollectorProxyInterface;

readonly class ApplyRoutesSubscriber
{
    public function onApplyRoutes(ApplyRoutesEvent $routes): void
    {
        $routes->group('/foo/bar', function (RouteCollectorProxyInterface $r) {
            $r->get('/baz', MyInvokableClass::class);
            $r->get('/bap', MyOtherInvokableClass::class);
        })->add(SomeMiddleWareForWholeGroup::class);
    }
}
```

### `redirect`

The `redirect` method sets up a redirect from a URI pattern to another URI.

Example:

```php
use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;

readonly class ApplyRoutesSubscriber
{
    public function onApplyRoutes(ApplyRoutesEvent $routes): void
    {
        // Status code optional
        $routes->redirect('/old/route', '/new/route', 301);
    }
}
```

### `getContainer`

It may be necessary from time to time to use the container while setting up routes. This method makes the container avaialble to you.
