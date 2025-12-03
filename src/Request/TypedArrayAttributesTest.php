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
