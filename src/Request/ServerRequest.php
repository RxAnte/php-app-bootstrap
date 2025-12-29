<?php

declare(strict_types=1);

namespace RxAnte\AppBootstrap\Request;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint

final readonly class ServerRequest implements ServerRequestInterface
{
    public TypedArrayAttributes $serverParams;

    public TypedArrayAttributes $cookieParams;

    public TypedArrayAttributes $queryParams;

    public TypedArrayAttributes $parsedBody;

    public TypedArrayAttributes $attributes;

    public function __construct(private ServerRequestInterface $request)
    {
        $this->serverParams = new TypedArrayAttributes(
            $request->getServerParams(),
        );

        $this->cookieParams = new TypedArrayAttributes(
            $request->getCookieParams(),
        );

        $this->queryParams = new TypedArrayAttributes(
            $request->getQueryParams(),
        );

        $this->parsedBody = new TypedArrayAttributes(
            /** @phpstan-ignore-next-line */
            $request->getParsedBody() ?? [],
        );

        $this->attributes = new TypedArrayAttributes(
            $this->request->getAttributes(),
        );
    }

    /**
     * ---- PSR-7 delegation ---------------------------------------------------
     */
    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    /** @inheritDoc */
    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->request->hasHeader($name);
    }

    /** @inheritDoc */
    public function getHeader(string $name): array
    {
        return $this->request->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->request->getHeaderLine($name);
    }

    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getServerParams(): array
    {
        return $this->request->getServerParams();
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }

    /**
     * @phpstan-ignore-next-line
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function getParsedBody(): mixed
    {
        return $this->request->getParsedBody();
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * ---- PSR-7 mutators (wrap returned instance) ----------------------------
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        return new self($this->request->withProtocolVersion(
            $version,
        ));
    }

    /** @inheritDoc */
    public function withHeader(string $name, $value): MessageInterface
    {
        return new self($this->request->withHeader(
            $name,
            $value,
        ));
    }

    /** @inheritDoc */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return new self($this->request->withAddedHeader(
            $name,
            $value,
        ));
    }

    public function withoutHeader(string $name): MessageInterface
    {
        return new self($this->request->withoutHeader($name));
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return new self($this->request->withBody($body));
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        return new self($this->request->withRequestTarget(
            $requestTarget,
        ));
    }

    public function withMethod(string $method): RequestInterface
    {
        return new self($this->request->withMethod($method));
    }

    public function withUri(
        UriInterface $uri,
        bool $preserveHost = false,
    ): RequestInterface {
        return new self($this->request->withUri(
            $uri,
            $preserveHost,
        ));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        return new self($this->request->withCookieParams(
            $cookies,
        ));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        return new self($this->request->withQueryParams(
            $query,
        ));
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function withUploadedFiles(
        array $uploadedFiles,
    ): ServerRequestInterface {
        return new self($this->request->withUploadedFiles(
            $uploadedFiles,
        ));
    }

    /** @phpstan-ignore-next-line */
    public function withParsedBody($data): ServerRequestInterface
    {
        return new self($this->request->withParsedBody($data));
    }

    public function withAttribute(
        string $name,
        mixed $value,
    ): ServerRequestInterface {
        return new self($this->request->withAttribute(
            $name,
            $value,
        ));
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        return new self($this->request->withoutAttribute(
            $name,
        ));
    }
}
