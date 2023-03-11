<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

final class Request extends Message implements RequestInterface
{
    public string $requestTarget = '';

    public function __construct(
        public string $method,
        public UriInterface $uri
    ) {
        parent::__construct();
    }

    public function withRequestTarget($requestTarget): self
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withMethod($method): self
    {
        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withUri($uri, $preserveHost = false): self
    {
        $clone = clone $this;
        $clone->uri = $uri;
        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }
}
