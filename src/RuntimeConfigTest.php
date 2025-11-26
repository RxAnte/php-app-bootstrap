<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap;

use Exception;
use InvalidArgumentException;
use RxAnte\Tests\RuntimeConfig\RuntimeConfigDirectory;
use RxAnte\Tests\RuntimeConfig\RuntimeConfigOptions;

use function expect;
use function it;
use function putenv;
use function uses;

uses()->group('Bootstrap', 'RuntimeConfig');

it(
    'gets string value',
    function (): void {
        putenv('TEST_2=TEST_2_ENV_VALUE');
        putenv('TEST_3=TEST_3_VALUE');

        $sut = new RuntimeConfig(
            RuntimeConfigDirectory::PATH . '/Secrets',
        );

        $test1 = $sut->getString(RuntimeConfigOptions::TEST_1);
        expect($test1)->toBe('TEST_1_VALUE');

        $test2 = $sut->getString(RuntimeConfigOptions::TEST_2);
        expect($test2)->toBe('TEST_2_ENV_VALUE');

        $test3 = $sut->getString(RuntimeConfigOptions::TEST_3);
        expect($test3)->toBe('TEST_3_VALUE');

        $test4 = $sut->getString(
            RuntimeConfigOptions::TEST_4,
            'defaultVal',
        );

        expect($test4)->toBe('defaultVal');

        $exception = new Exception();

        try {
            $sut->getString(RuntimeConfigOptions::TEST_4);
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        expect($exception)->toBeInstanceOf(
            InvalidArgumentException::class,
        );

        expect($exception->getMessage())->toBe(
            'TEST_4 could not be found in secrets or environment variables and no default value was provided',
        );
    },
);

it(
    'gets boolean value',
    function (): void {
        putenv('TEST_FALSY_ENV=0');
        putenv('TEST_TRUTHY_ENV=1');

        $sut = new RuntimeConfig(
            RuntimeConfigDirectory::PATH . '/Secrets',
        );

        $testFalsy = $sut->getBoolean(RuntimeConfigOptions::TEST_FALSY);
        expect($testFalsy)->toBeFalse();

        $testTruthy = $sut->getBoolean(RuntimeConfigOptions::TEST_TRUTHY);
        expect($testTruthy)->toBeTrue();

        $testFalsyEnv = $sut->getBoolean(
            RuntimeConfigOptions::TEST_FALSY_ENV,
        );
        expect($testFalsyEnv)->toBeFalse();

        $testTruthyEnv = $sut->getBoolean(
            RuntimeConfigOptions::TEST_TRUTHY_ENV,
        );
        expect($testTruthyEnv)->toBeTrue();

        $test4 = $sut->getBoolean(
            RuntimeConfigOptions::TEST_4,
            true,
        );

        expect($test4)->toBeTrue();

        $exception = new Exception();

        try {
            $sut->getBoolean(RuntimeConfigOptions::TEST_4);
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        expect($exception)->toBeInstanceOf(
            InvalidArgumentException::class,
        );

        expect($exception->getMessage())->toBe(
            'TEST_4 could not be found in secrets or environment variables and no default value was provided',
        );
    },
);

it(
    'gets integer value',
    function (): void {
        putenv('TEST_INT_ENV=987');

        $sut = new RuntimeConfig(
            RuntimeConfigDirectory::PATH . '/Secrets',
        );

        $testInt = $sut->getInteger(RuntimeConfigOptions::TEST_INT);
        expect($testInt)->toBe(456);

        $testIntEnv = $sut->getInteger(RuntimeConfigOptions::TEST_INT_ENV);
        expect($testIntEnv)->toBe(987);

        $test4 = $sut->getInteger(
            RuntimeConfigOptions::TEST_4,
            876,
        );

        expect($test4)->toBe(876);

        $exception = new Exception();

        try {
            $sut->getInteger(RuntimeConfigOptions::TEST_4);
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        expect($exception)->toBeInstanceOf(
            InvalidArgumentException::class,
        );

        expect($exception->getMessage())->toBe(
            'TEST_4 could not be found in secrets or environment variables and no default value was provided',
        );
    },
);
