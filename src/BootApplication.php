<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use RxAnte\AppBootstrap\Cli\BootCommands;
use RxAnte\AppBootstrap\Cli\CliConfig;
use RxAnte\AppBootstrap\Http\BootHttpRoutes;
use Silly\Application;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorInterface;

use function assert;

readonly class BootApplication
{
    public function __construct(
        private ContainerInterface $container,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function buildHttpApplication(): BootHttpRoutes
    {
        $routeCollector = $this->container->get(
            RouteCollectorInterface::class,
        );

        assert($routeCollector instanceof RouteCollectorInterface);

        $app = AppFactory::create(
            container: $this->container,
            routeCollector: $routeCollector,
        );

        return new BootHttpRoutes(
            $app,
            $this->eventDispatcher,
        );
    }

    public function buildCliApplication(CliConfig $cliConfig): BootCommands
    {
        $app = new Application($cliConfig->cliAppName);

        $app->useContainer($this->container);

        return new BootCommands(
            $app,
            $this->eventDispatcher,
        );
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
