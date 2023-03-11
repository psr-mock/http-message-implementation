<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class Message implements MessageInterface
{
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private StreamInterface $body;

    public static function create(
        string $protocolVersion = '1.1',
        array $headers = [],
        ?StreamInterface $body = null
    ): static {
        $message = new static();
        $message->protocolVersion = $protocolVersion;
        $message->headers = $headers;
        $message->body = $body ?? new Stream();

        return $message;
    }

    public function __construct() {
        $this->body = new Stream();
    }

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): self
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): self
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    public function withHeaders($headers): self
    {
        $clone = clone $this;
        $clone->headers = $headers;
        return $clone;
    }

    public function withAddedHeader($name, $value): self
    {
        $clone = clone $this;
        $clone->headers[$name] ??= [];
        $clone->headers[$name][] = $value;
        return $clone;
    }

    public function withoutHeader($name): self
    {
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(
        StreamInterface $body
    ) {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }
}
