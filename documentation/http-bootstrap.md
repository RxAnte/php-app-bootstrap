# HTTP Bootstrap

Though booting the Web App and the CLI App start with the same boot class, they do split into different paths. Web Apps need to register routes and respond to HTTP requests. CLI Apps will need to register commands with arguments and such.

This document shows how to boot up the HTTP application. The following example code would be placed in your application's `index.php` file.

```php
<?php

declare(strict_types=1);

use Config\Dependencies\RegisterBindings;
use Config\Events\RegisterEventSubscribers;
use RxAnte\AppBootstrap\Boot;
use RxAnte\AppBootstrap\BootConfig;
use RxAnte\AppBootstrap\Dependencies\BuildContainerConfiguration;
use RxAnte\AppBootstrap\Http\BootHttpMiddlewareConfig;

/**
 * This assumes your `vendor` directory is one directory above webroot
 * (and it definitely should be)
 */

require dirname(__DIR__) . '/vendor/autoload.php';

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
        [RegisterBindings::class, 'register'],
        // Optional container configuration
        new BuildContainerConfiguration(
            enableCompilationToDir: '/path/to/dir',
            writeProxiesToDir: '/path/to/dir',
        ),
    )
    /**
     * This example works similarly to `RegisterBindings`. The `register`
     * method will receive an instance of `\Crell\Tukio\OrderedProviderInterface`
     * with which you can register event bindings.
     */
    ->registerEventSubscribers([RegisterEventSubscribers::class, 'register'])
    /**
     * This is where you would split off in your CLI entry point
     */
    ->buildHttpApplication()
    ->applyRoutes()
    ->applyMiddleware(new BootHttpMiddlewareConfig(
        useProductionErrorMiddleware: true,
        customProductionErrorMiddlewareHandler: 'SOME_HANDLER',
        productionErrorMiddlewareLogger: 'SOME_LOGGER',
    ))
    ->runApplication();
```
