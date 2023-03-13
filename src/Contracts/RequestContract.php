<?php

declare(strict_types=1);

namespace PsrMock\Psr7\Contracts;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

interface RequestContract extends RequestInterface
{
    public function withRequestTarget(mixed $requestTarget): static;

    public function getRequestTarget(): string;

    public function withMethod($method): static;

    public function getMethod(): string;

    public function withUri(UriInterface $uri, $preserveHost = false): static;

    public function getUri(): UriInterface;
}
