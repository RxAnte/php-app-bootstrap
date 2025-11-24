<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use Composer\ClassMapGenerator\ClassMapGenerator;

use function array_keys;
use function array_map;
use function call_user_func_array;

readonly class RegisterBindingsInDirectories
{
    /** @param string[] $directories */
    public function __construct(private array $directories)
    {
        array_map(
            static fn (string $d) => $d,
            $directories,
        );
    }

    public function __invoke(Bindings $bindings): void
    {
        array_map(
            fn (string $directory) => $this->registerDirectory(
                $directory,
                $bindings,
            ),
            $this->directories,
        );
    }

    private function registerDirectory(
        string $directory,
        Bindings $bindings,
    ): void {
        $generator = new ClassMapGenerator();
        $generator->scanPaths($directory);

        $classStrings = array_keys($generator->getClassMap()->map);

        array_map(
            static function (
                string $classString,
            ) use ($bindings): void {
                call_user_func_array(
                    /** @phpstan-ignore-next-line */
                    new $classString(),
                    [$bindings],
                );
            },
            $classStrings,
        );
    }
}
