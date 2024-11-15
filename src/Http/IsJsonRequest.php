<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Http;

use Psr\Http\Message\ServerRequestInterface;

use function explode;
use function in_array;

readonly class IsJsonRequest
{
    public function checkHttpAcceptString(string $httpAccept): bool
    {
        $httpAccept = $httpAccept !== '' ? $httpAccept : 'text/html';

        $acceptArray = explode(',', $httpAccept);

        return in_array(
            'application/json',
            $acceptArray,
            true,
        );
    }

    public function checkRequest(ServerRequestInterface $request): bool
    {
        return $this->checkHttpAcceptString(
            $request->getHeader('Accept')[0] ?? '',
        );
    }
}
