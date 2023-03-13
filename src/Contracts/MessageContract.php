<?php

declare(strict_types=1);

namespace PsrMock\Psr7\Contracts;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

interface MessageContract extends MessageInterface
{
    public function getProtocolVersion(): string;

    public function withProtocolVersion($version): static;

    public function getHeaders(): array;

    public function hasHeader($name): bool;

    public function getHeader($name): array;

    public function getHeaderLine($name): string;

    /**
     * @param string $name
     * @param string|string[] $value
     */
    public function withHeader($name, $value): static;

    /**
     * @param string[][] $headers
     */
    public function withHeaders(array $headers): static;

    /**
     * @param string $name
     * @param string|string[] $value
     */
    public function withAddedHeader($name, $value): static;

    public function withoutHeader($name): static;

    public function getBody(): StreamInterface;

    public function withBody(StreamInterface $body): static;
}
