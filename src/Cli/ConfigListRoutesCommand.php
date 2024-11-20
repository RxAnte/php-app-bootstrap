<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Cli;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use RxAnte\AppBootstrap\Http\ApplyRoutesEvent;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

use function implode;
use function is_string;

readonly class ConfigListRoutesCommand
{
    public static function applyCommand(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(
            'config:list-routes',
            self::class,
        );
    }

    public function __construct(
        private ContainerInterface $container,
        private EventDispatcherInterface $eventDispatcher,
        private ConsoleOutputInterface $output,
    ) {
    }

    public function __invoke(): void
    {
        $app = AppFactory::create(container: $this->container);

        $this->eventDispatcher->dispatch(new ApplyRoutesEvent(
            $app,
        ));

        $table = new Table($this->output);
        $table->setHeaders(['Method', 'Path', 'Callable']);

        $routes = [];

        foreach ($app->getRouteCollector()->getRoutes() as $route) {
            $callable = $route->getCallable();

            $routes[] = [
                implode(', ', $route->getMethods()),
                $route->getPattern(),
                (is_string($callable) ? $callable : 'anonymous function'),
            ];
        }

        $table->setRows($routes);
        $table->render();
    }
}
