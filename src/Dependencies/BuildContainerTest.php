<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Dependencies;

use Psr\Http\Message\ServerRequestInterface;

use function expect;
use function test;
use function uses;

uses()->group('AppBootstrap', 'BuildContainer');

test(
    'Built container returns a server request',
    function (): void {
        $container = BuildContainer::build();

        expect($container->get(ServerRequestInterface::class))
            ->toBeInstanceOf(ServerRequestInterface::class);
    },
);
