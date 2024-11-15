<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use RxAnte\AppBootstrap\Http\IsJsonRequest;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

use function class_exists;
use function error_reporting;
use function ini_set;

use const E_ALL;

readonly class Boot
{
    public function start(BootConfig $config = new BootConfig()): BootContainer
    {
        if ($config->useWhoopsErrorHandling) {
            $this->registerDevErrorHandling($config->isCli);
        }

        return new BootContainer();
    }

    private function registerDevErrorHandling(bool $isCli): void
    {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        if (! class_exists(WhoopsRun::class)) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        $whoops = new WhoopsRun();

        if ($isCli) {
            $handler = new PlainTextHandler();
        } elseif ($this->isJsonRequest()) {
            $handler = new JsonResponseHandler();
        } else {
            $handler = new PrettyPageHandler();
        }

        $whoops->prependHandler($handler);

        $whoops->register();
    }

    private function isJsonRequest(): bool
    {
        return (new IsJsonRequest())->checkHttpAcceptString(
            httpAccept: (string) ($_SERVER['HTTP_ACCEPT'] ?? ''),
        );
    }
}
