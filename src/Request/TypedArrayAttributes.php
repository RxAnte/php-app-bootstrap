<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Request;

use UnexpectedValueException;

use function array_filter;
use function array_key_exists;
use function array_map;
use function array_values;
use function is_array;
use function is_float;
use function is_numeric;
use function is_scalar;
use function is_string;
use function mb_strtolower;
use function sprintf;
use function trim;

readonly class TypedArrayAttributes
{
    /** @param array<string, mixed> $attributes */
    public function __construct(public array $attributes)
    {
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    private function isIntAble(mixed $val): bool
    {
        $val = is_string($val) ? trim($val) : $val;

        return $val !== '' &&
            is_numeric($val) &&
            ! is_float($val + 0);
    }

    public function getInt(string $name): int
    {
        $val = $this->attributes[$name] ?? null;

        if (
            ! $this->has($name) ||
            ! is_scalar($val) ||
            ! $this->isIntAble($val)
        ) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be int.',
                $name,
            ));
        }

        return (int) $val;
    }

    /** @return ($fallback is null ? int|null : int) */
    public function findInt(
        string $name,
        int|null $fallback = null,
    ): int|null {
        try {
            return $this->getInt($name);
        } catch (UnexpectedValueException) {
            return $fallback;
        }
    }

    private function isFloatable(mixed $val): bool
    {
        $val = is_string($val) ? trim($val) : $val;

        return $val !== '' && is_numeric($val);
    }

    public function getFloat(
        string $name,
    ): float {
        $val = $this->attributes[$name] ?? null;

        if (
            ! $this->has($name) ||
            ! is_scalar($val) ||
            ! $this->isFloatable($val)
        ) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be float.',
                $name,
            ));
        }

        return (float) $val;
    }

    /** @return ($fallback is null ? float|null : float) */
    public function findFloat(
        string $name,
        float|null $fallback = null,
    ): float|null {
        try {
            return $this->getFloat($name);
        } catch (UnexpectedValueException) {
            return $fallback;
        }
    }

    public function getString(string $name): string
    {
        $val = $this->attributes[$name] ?? null;

        if (
            ! $this->has($name) ||
            ! is_scalar($val)
        ) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be string.',
                $name,
            ));
        }

        return (string) $val;
    }

    /** @return ($fallback is null ? string|null : string) */
    public function findString(
        string $name,
        string|null $fallback = null,
    ): string|null {
        try {
            return $this->getString($name);
        } catch (UnexpectedValueException) {
            return $fallback;
        }
    }

    public function getBoolean(string $name): bool
    {
        $val = $this->attributes[$name] ?? null;

        if (
            ! $this->has($name) ||
            ! is_scalar($val)
        ) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be boolean.',
                $name,
            ));
        }

        return $this->castBool($val);
    }

    private function castBool(mixed $val): bool
    {
        if (is_string($val)) {
            $val = mb_strtolower($val);
        }

        if ($val === 'true') {
            return true;
        }

        if ($val === 'false') {
            return false;
        }

        return (bool) $val;
    }

    /** @return ($fallback is null ? bool|null : bool) */
    public function findBoolean(
        string $name,
        bool|null $fallback = null,
    ): bool|null {
        try {
            return $this->getBoolean($name);
        } catch (UnexpectedValueException) {
            return $fallback;
        }
    }

    /** @return mixed[] */
    public function getArray(string $name): array
    {
        $val = $this->attributes[$name] ?? null;

        if (! $this->has($name) || ! is_array($val)) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be array.',
                $name,
            ));
        }

        return $val;
    }

    /** @return mixed[] */
    public function findArray(string $name): array
    {
        if (! $this->has($name)) {
            return [];
        }

        $val = $this->attributes[$name];

        if (! is_array($val)) {
            return [];
        }

        return $val;
    }

    /** @return string[] */
    public function getArrayStrings(string $name): array
    {
        $val = $this->attributes[$name] ?? null;

        if (! $this->has($name) || ! is_array($val)) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be array of strings.',
                $name,
            ));
        }

        foreach ($val as $item) {
            if (! is_string($item)) {
                throw new UnexpectedValueException(sprintf(
                    'Expected "%s" attribute to be array of strings.',
                    $name,
                ));
            }
        }

        /** @phpstan-ignore-next-line */
        return $val;
    }

    /** @return string[] */
    public function findArrayStrings(string $name): array
    {
        if (! $this->has($name)) {
            return [];
        }

        $val = $this->attributes[$name];

        if (! is_array($val)) {
            return [];
        }

        return array_values(array_filter(
            $val,
            static fn (mixed $item): bool => is_string($item),
        ));
    }

    /** @return bool[] */
    public function getArrayBooleans(string $name): array
    {
        $val = $this->attributes[$name] ?? null;

        if (! $this->has($name) || ! is_array($val)) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be array of booleans.',
                $name,
            ));
        }

        foreach ($val as $item) {
            if (! is_scalar($item)) {
                throw new UnexpectedValueException(sprintf(
                    'Expected "%s" attribute to be array of booleans.',
                    $name,
                ));
            }
        }

        return array_values(array_map(
            fn (mixed $item): bool => $this->castBool($item),
            $val,
        ));
    }

    /** @return bool[] */
    public function findArrayBooleans(string $name): array
    {
        if (! $this->has($name)) {
            return [];
        }

        $val = $this->attributes[$name];

        if (! is_array($val)) {
            return [];
        }

        return array_values(array_map(
            fn (mixed $item): bool => $this->castBool($item),
            array_filter(
                $val,
                static fn (mixed $item): bool => is_scalar($item),
            ),
        ));
    }

    /** @return float[] */
    public function getArrayFloats(string $name): array
    {
        $val = $this->attributes[$name] ?? null;

        if (! $this->has($name) || ! is_array($val)) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be array of floats.',
                $name,
            ));
        }

        foreach ($val as $item) {
            if (! is_scalar($item) || ! $this->isFloatable($item)) {
                throw new UnexpectedValueException(sprintf(
                    'Expected "%s" attribute to be array of floats.',
                    $name,
                ));
            }
        }

        return array_values(array_map(
            /** @phpstan-ignore-next-line */
            static fn (mixed $item): float => (float) $item,
            $val,
        ));
    }

    /** @return float[] */
    public function findArrayFloats(string $name): array
    {
        if (! $this->has($name)) {
            return [];
        }

        $val = $this->attributes[$name];

        if (! is_array($val)) {
            return [];
        }

        return array_values(array_map(
            static fn (mixed $item): float => (float) $item,
            array_filter(
                $val,
                fn (mixed $item): bool => is_scalar($item) &&
                    $this->isFloatable($item),
            ),
        ));
    }

    /** @return int[] */
    public function getArrayInts(string $name): array
    {
        $val = $this->attributes[$name] ?? null;

        if (! $this->has($name) || ! is_array($val)) {
            throw new UnexpectedValueException(sprintf(
                'Expected "%s" attribute to be array of ints.',
                $name,
            ));
        }

        foreach ($val as $item) {
            if (! $this->isIntAble($item)) {
                throw new UnexpectedValueException(sprintf(
                    'Expected "%s" attribute to be array of ints.',
                    $name,
                ));
            }
        }

        return array_values(array_map(
            /** @phpstan-ignore-next-line */
            static fn (mixed $item): int => (int) $item,
            $val,
        ));
    }

    /** @return int[] */
    public function findArrayInts(string $name): array
    {
        if (! $this->has($name)) {
            return [];
        }

        $val = $this->attributes[$name];

        if (! is_array($val)) {
            return [];
        }

        return array_values(array_map(
            static fn (mixed $item): int => (int) $item,
            array_filter(
                $val,
                fn (mixed $item): bool => is_scalar($item) &&
                    $this->isIntAble($item),
            ),
        ));
    }
}
