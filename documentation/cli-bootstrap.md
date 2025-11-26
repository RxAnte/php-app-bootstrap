# CLI Bootstrap

Though booting the Web App and the CLI App start with the same boot class, they do split into different paths. Web Apps need to register routes and respond to HTTP requests. CLI Apps will need to register commands with arguments and such.

This document shows how to boot up the CLI application. The following example code would be placed in your application's cli entrypoint PHP file.

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
    ->start(new BootConfig(
        isCli: false,
        useWhoopsErrorHandling: false, // Set to true in dev (use env variables perhaps)
    ))
    /**
     * This is an example of using a class with method of `register` to add
     * your bindings to the container (PHP-DI). The method will receive an
     * instance of `\RxAnte\AppBootstrap\Dependencies\Bindings` as the first
     * argument
     */
    ->buildContainer(
        /**
         * `register` can take callable, string, or array of strings
         * callable: the callable receives an instance of
         *           `\RxAnte\AppBootstrap\Dependencies\Bindings` to register
         *           any custom bindings
         * string: a directory path containing callable classes used to
         *         register dependencies. The `__invoke` method will receive
         *         an instance of `\RxAnte\AppBootstrap\Dependencies\Bindings`
         * array of strings: multiple directory paths containing callable
         *                   classes for registering dependencies
         */
        register: [RegisterBindings::class, 'register'],
        // Optional container configuration
        config: new BuildContainerConfiguration(
            enableCompilationToDir: '/path/to/dir',
            writeProxiesToDir: '/path/to/dir',
        ),
    )
    /**
     * `register` can take a callable, string, or array of strings
     * callable: the callable receives an instance of
     *           `\Crell\Tukio\OrderedProviderInterface` to register events
     * string: a directory path containing callable classes used to register
     *         events. The `__invoke` method will receive an instance of
     *         `\Crell\Tukio\OrderedProviderInterface`
     * array of strings: multiple directory paths containing callable classes
     *                   for registering events
     */
    ->registerEventSubscribers([RegisterEventSubscribers::class, 'register'])
    /**
     * This is where you would split off in your HTTP entry point
     */
    ->buildCliApplication()
    ->applyCommands()
    ->runApplication();
```
