<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Crell\Tukio\OrderedProviderInterface;

use function array_keys;
use function array_map;

readonly class RegisterEventSubscribersInDirectories
{
    public function __construct(private OrderedProviderInterface $provider)
    {
    }

    /** @param string[] $directories */
    public function register(array $directories): void
    {
        array_map(
            fn (string $directory) => $this->registerDirectory(
                $directory,
                $this->provider,
            ),
            $directories,
        );
    }

    private function registerDirectory(
        string $directory,
        OrderedProviderInterface $provider,
    ): void {
        $generator = new ClassMapGenerator();
        $generator->scanPaths($directory);

        $classStrings = array_keys($generator->getClassMap()->map);

        array_map(
            static function (
                string $classString,
            ) use ($provider): void {
                $provider->addSubscriber($classString);
            },
            $classStrings,
        );
    }
}
