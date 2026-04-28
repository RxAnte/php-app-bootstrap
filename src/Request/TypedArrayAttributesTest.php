<?php

declare(strict_types=1);

use RxAnte\AppBootstrap\Request\TypedArrayAttributes;

uses()->group('Bootstrap', 'TypedArrayAttributes');

it(
    'returns correct value for `has`',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => 321,
            'bar' => '098',
            'baz' => 'asdf',
        ]);

        expect($sut->has('foo'))->toBeTrue();
        expect($sut->has('bar'))->toBeTrue();
        expect($sut->has('baz'))->toBeTrue();
        expect($sut->has('asdf'))->toBeFalse();
    },
);

it(
    'gets int from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => 321,
            'bar' => '098',
            'baz' => 'asdf',
        ]);

        $foo = $sut->getInt('foo');
        expect($foo)->toBe(321);
        $foo2 = $sut->findInt('foo');
        expect($foo2)->toBe(321);

        $bar = $sut->getInt('bar');
        expect($bar)->toBe(98);
        $bar = $sut->findInt('bar');
        expect($bar)->toBe(98);

        $baz = $sut->findInt('baz');
        expect($baz)->toBeNull();
        $baz2 = $sut->findInt('baz', 11);
        expect($baz2)->toBe(11);
    },
);

it(
    'gets float from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => 443,
            'foo-thing' => 876.4,
            'bar' => '098.65',
            'baz' => 'asdf',
        ]);

        $foo = $sut->getFloat('foo');
        expect($foo)->toBe(443.0);
        $foo2 = $sut->findFloat('foo');
        expect($foo2)->toBe(443.0);

        $fooThing = $sut->getFloat('foo-thing');
        expect($fooThing)->toBe(876.4);
        $fooThing2 = $sut->findFloat('foo-thing');
        expect($fooThing2)->toBe(876.4);

        $bar = $sut->getFloat('bar');
        expect($bar)->toBe(98.65);
        $bar2 = $sut->findFloat('bar');
        expect($bar2)->toBe(98.65);

        $baz = $sut->findFloat('baz');
        expect($baz)->toBeNull();
        $baz2 = $sut->findFloat('baz', 11);
        expect($baz2)->toBe(11.0);
        $baz2 = $sut->findFloat('baz', 123.4);
        expect($baz2)->toBe(123.4);
    },
);

it(
    'gets string from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => 443,
            'foo-thing' => 876.4,
            'bar' => '098.65',
        ]);

        $foo = $sut->getString('foo');
        expect($foo)->toBe('443');
        $foo2 = $sut->findString('foo');
        expect($foo2)->toBe('443');

        $fooThing = $sut->getString('foo-thing');
        expect($fooThing)->toBe('876.4');
        $fooThing2 = $sut->findString('foo-thing');
        expect($fooThing2)->toBe('876.4');

        $bar = $sut->getString('bar');
        expect($bar)->toBe('098.65');
        $bar2 = $sut->findString('bar');
        expect($bar2)->toBe('098.65');

        $baz = $sut->findString('baz');
        expect($baz)->toBeNull();
        $baz2 = $sut->findString('baz', 'test-123');
        expect($baz2)->toBe('test-123');
    },
);

it(
    'gets boolean from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => 443,
            'foo2' => 876.4,
            'foo3' => '098.65',
            'foo4' => '1',
            'foo5' => '0',
            'foo6' => 'true',
            'foo7' => true,
            'foo8' => 'false',
            'foo9' => false,
            'foo10' => 1,
            'foo11' => 0,
        ]);

        expect($sut->getBoolean('foo'))->toBeTrue();
        expect($sut->findBoolean('foo'))->toBeTrue();

        expect($sut->getBoolean('foo2'))->toBeTrue();
        expect($sut->findBoolean('foo2'))->toBeTrue();

        expect($sut->getBoolean('foo3'))->toBeTrue();
        expect($sut->findBoolean('foo3'))->toBeTrue();

        expect($sut->getBoolean('foo4'))->toBeTrue();
        expect($sut->findBoolean('foo4'))->toBeTrue();

        expect($sut->getBoolean('foo5'))->toBeFalse();
        expect($sut->findBoolean('foo5'))->toBeFalse();

        expect($sut->getBoolean('foo6'))->toBeTrue();
        expect($sut->findBoolean('foo6'))->toBeTrue();

        expect($sut->getBoolean('foo7'))->toBeTrue();
        expect($sut->findBoolean('foo7'))->toBeTrue();

        expect($sut->getBoolean('foo8'))->toBeFalse();
        expect($sut->findBoolean('foo8'))->toBeFalse();

        expect($sut->getBoolean('foo9'))->toBeFalse();
        expect($sut->findBoolean('foo9'))->toBeFalse();

        expect($sut->getBoolean('foo10'))->toBeTrue();
        expect($sut->findBoolean('foo10'))->toBeTrue();

        expect($sut->getBoolean('foo11'))->toBeFalse();
        expect($sut->findBoolean('foo11'))->toBeFalse();

        expect($sut->findBoolean('baz'))->toBeNull();
        $baz2 = $sut->findBoolean('baz', true);
        expect($baz2)->toBeTrue();
        $baz3 = $sut->findBoolean('baz', false);
        expect($baz3)->toBeFalse();
    },
);

it(
    'gets array of mixed from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => ['bar', 123, true],
            'non-array' => 'not-an-array',
        ]);

        $foo = $sut->getArray('foo');
        expect($foo)->toBe(['bar', 123, true]);

        $foo2 = $sut->findArray('foo');
        expect($foo2)->toBe(['bar', 123, true]);

        expect(fn () => $sut->getArray('non-array'))
            ->toThrow(UnexpectedValueException::class);

        $nonArray = $sut->findArray('non-array');
        expect($nonArray)->toBe([]);

        expect(fn () => $sut->getArray('missing'))
            ->toThrow(UnexpectedValueException::class);

        $missing = $sut->findArray('missing');
        expect($missing)->toBe([]);
    },
);

it(
    'gets array of strings from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => ['bar', 'baz'],
            'mixed' => ['hello', 123, 'world', true],
            'non-array' => 'not-an-array',
        ]);

        $foo = $sut->getArrayStrings('foo');
        expect($foo)->toBe(['bar', 'baz']);

        $foo2 = $sut->findArrayStrings('foo');
        expect($foo2)->toBe(['bar', 'baz']);

        expect(fn () => $sut->getArrayStrings('mixed'))
            ->toThrow(UnexpectedValueException::class);

        $mixed = $sut->findArrayStrings('mixed');
        expect($mixed)->toBe(['hello', 'world']);

        expect(fn () => $sut->getArrayStrings('non-array'))
            ->toThrow(UnexpectedValueException::class);

        $nonArray = $sut->findArrayStrings('non-array');
        expect($nonArray)->toBe([]);

        expect(fn () => $sut->getArrayStrings('missing'))
            ->toThrow(UnexpectedValueException::class);

        $missing = $sut->findArrayStrings('missing');
        expect($missing)->toBe([]);
    },
);

it(
    'gets array of booleans from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => [true, false, true],
            'strings' => ['true', 'false', '1', '0'],
            'numeric' => [1, 0, 443],
            'mixed' => [true, ['nested'], false],
            'non-array' => 'not-an-array',
        ]);

        $foo = $sut->getArrayBooleans('foo');
        expect($foo)->toBe([true, false, true]);

        $foo2 = $sut->findArrayBooleans('foo');
        expect($foo2)->toBe([true, false, true]);

        $strings = $sut->getArrayBooleans('strings');
        expect($strings)->toBe([true, false, true, false]);

        $strings2 = $sut->findArrayBooleans('strings');
        expect($strings2)->toBe([true, false, true, false]);

        $numeric = $sut->getArrayBooleans('numeric');
        expect($numeric)->toBe([true, false, true]);

        $numeric2 = $sut->findArrayBooleans('numeric');
        expect($numeric2)->toBe([true, false, true]);

        expect(fn () => $sut->getArrayBooleans('mixed'))
            ->toThrow(UnexpectedValueException::class);

        $mixed = $sut->findArrayBooleans('mixed');
        expect($mixed)->toBe([true, false]);

        expect(fn () => $sut->getArrayBooleans('non-array'))
            ->toThrow(UnexpectedValueException::class);

        $nonArray = $sut->findArrayBooleans('non-array');
        expect($nonArray)->toBe([]);

        expect(fn () => $sut->getArrayBooleans('missing'))
            ->toThrow(UnexpectedValueException::class);

        $missing = $sut->findArrayBooleans('missing');
        expect($missing)->toBe([]);
    },
);

it(
    'gets array of floats from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => [1.1, 2.2, 3.3],
            'ints' => [4, 5, 6],
            'strings' => ['7.7', '8.8', '9'],
            'mixed' => [1.1, 'hello', 2.2, true],
            'non-array' => 'not-an-array',
        ]);

        $foo = $sut->getArrayFloats('foo');
        expect($foo)->toBe([1.1, 2.2, 3.3]);

        $foo2 = $sut->findArrayFloats('foo');
        expect($foo2)->toBe([1.1, 2.2, 3.3]);

        $ints = $sut->getArrayFloats('ints');
        expect($ints)->toBe([4.0, 5.0, 6.0]);

        $ints2 = $sut->findArrayFloats('ints');
        expect($ints2)->toBe([4.0, 5.0, 6.0]);

        $strings = $sut->getArrayFloats('strings');
        expect($strings)->toBe([7.7, 8.8, 9.0]);

        $strings2 = $sut->findArrayFloats('strings');
        expect($strings2)->toBe([7.7, 8.8, 9.0]);

        expect(fn () => $sut->getArrayFloats('mixed'))
            ->toThrow(UnexpectedValueException::class);

        $mixed = $sut->findArrayFloats('mixed');
        expect($mixed)->toBe([1.1, 2.2]);

        expect(fn () => $sut->getArrayFloats('non-array'))
            ->toThrow(UnexpectedValueException::class);

        $nonArray = $sut->findArrayFloats('non-array');
        expect($nonArray)->toBe([]);

        expect(fn () => $sut->getArrayFloats('missing'))
            ->toThrow(UnexpectedValueException::class);

        $missing = $sut->findArrayFloats('missing');
        expect($missing)->toBe([]);
    },
);

it(
    'gets array of ints from attributes',
    function (): void {
        $sut = new TypedArrayAttributes([
            'foo' => [1, 2, 3],
            'strings' => ['4', '005', '6'],
            'mixed' => [7, 'hello', 8, true],
            'non-array' => 'not-an-array',
        ]);

        $foo = $sut->getArrayInts('foo');
        expect($foo)->toBe([1, 2, 3]);

        $foo2 = $sut->findArrayInts('foo');
        expect($foo2)->toBe([1, 2, 3]);

        $strings = $sut->getArrayInts('strings');
        expect($strings)->toBe([4, 5, 6]);

        $strings2 = $sut->findArrayInts('strings');
        expect($strings2)->toBe([4, 5, 6]);

        expect(fn () => $sut->getArrayInts('mixed'))
            ->toThrow(UnexpectedValueException::class);

        $mixed = $sut->findArrayInts('mixed');
        expect($mixed)->toBe([7, 8]);

        expect(fn () => $sut->getArrayInts('non-array'))
            ->toThrow(UnexpectedValueException::class);

        $nonArray = $sut->findArrayInts('non-array');
        expect($nonArray)->toBe([]);

        expect(fn () => $sut->getArrayInts('missing'))
            ->toThrow(UnexpectedValueException::class);

        $missing = $sut->findArrayInts('missing');
        expect($missing)->toBe([]);
    },
);
