# Register Bindings

During the [HTTP](http-bootstrap.md)/[CLI](cli-bootstrap.md) boot process, one of the stages is to register bindings on the [PSR-11 Container](https://www.php-fig.org/psr/psr-11/) ([PHP-DI](https://php-di.org) is the PSR-11 Container this package uses).

Registering bindings is important because, while the container will autowire everything it can, some things cannot be autowired and you will need to provide some configuration. Maybe you need to set up some primitives in the constructor of a class that needs an API key, etc. Or maybe you need to map an interface to a concrete implementation. Whatever the need, you can provide a callable as the first argument on the `buildContainer` method when chaining up the boot process.

```php
use Config\Dependencies\RegisterBindings;
use RxAnte\AppBootstrap\Boot;

require __DIR__ . '/vendor/autoload.php';

(new Boot())
    ->start()
    // You can use a callable, or a path that has callable classes here
    ->buildContainer([RegisterBindings::class, 'register'])
    // ...etc.
```

The callable will receive one argument, which is an instance of `RxAnte\AppBootstrap\Dependencies\Bindings` which can be used to set up the bindings.

As an example, the `RegisterBindings` class from above will look something like this:

```php
use BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand;
use Config\RuntimeConfig;
use Config\RuntimeConfigOptions;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use RxAnte\AppBootstrap\Dependencies\Bindings;
use Symfony\Component\Cache\Adapter\RedisAdapter;

readonly class RegisterBindings
{
    public static function register(Bindings $bindings): void
    {
        // Map an interface to a concrete implementation
        $bindings->addBinding(
            CacheItemPoolInterface::class,
            $bindings->resolveFromContainer(RedisAdapter::class),
        );

        // Use a callable as a factory to provide config for the concrete
        $bindings->addBinding(
            RedisAdapter::class,
            static function (ContainerInterface $container): RedisAdapter {
                $redis = $container->get(Redis::class);
                assert($redis instanceof Redis);

                return new RedisAdapter($redis, 'connect_api');
            },
        );

        // Another example of a factory to set up Redis
        $bindings->addBinding(
            Redis::class,
            static function (ContainerInterface $container): Redis {
                $redis = new Redis();

                $runtimeConfig = $container->get(RuntimeConfig::class);
                assert($runtimeConfig instanceof RuntimeConfig);

                $redis->connect($runtimeConfig->getString(
                    RuntimeConfigOptions::REDIS_HOST,
                ));

                return $redis;
            },
        );

        // Here's an example of letting the container autowire the dependencies
        // of the class it can, and specifying one constructor param
        $bindings->addBinding(
            QueueConsumeNextSymfonyCommand::class,
            $bindings->autowire(QueueConsumeNextSymfonyCommand::class)
                ->constructorParameter(
                    'name',
                    'queue:consume-next',
                ),
        );
    }
}
```
