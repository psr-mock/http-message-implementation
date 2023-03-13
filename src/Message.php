<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use PsrMock\Psr7\Collections\Headers;
use PsrMock\Psr7\Contracts\MessageContract;

abstract class Message implements MessageContract, MessageInterface
{
    /**
     * @param string $protocolVersion
     * @param null|Headers $headers
     * @param null|StreamInterface $stream
     *
     * @return void
     */
    public function __construct(
        private string $protocolVersion = '1.1',
        private ?Headers $headers = null,
        private ?StreamInterface $stream = null,
    ) {
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
    {
        if (! is_string($version)) {
            throw new \InvalidArgumentException('Protocol version must be a string');
        }

        $clone = clone $this;
        $clone->protocolVersion = strval($version);
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers()->all();
    }

    public function hasHeader($name): bool
    {
        return $this->headers()->has($name);
    }

    public function getHeader($name): array
    {
        return $this->headers()->get($name);
    }

    public function getHeaderLine($name): string
    {
        return $this->headers()->getString($name);
    }

    public function withHeader($name, $value): static
    {
        $clone = clone $this;

        if (is_array($value)) {
            foreach ($value as $v) {
                $clone->headers()->add($name, $v);
            }
        } elseif (is_string($value)) {
            $clone->headers()->add($name, $value);
        }

        return $clone;
    }

    public function withHeaders(array $headers): static
    {
        $clone = clone $this;

        foreach ($headers as $name => $value) {
            if (! is_string($name)) {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $v) {
                    $clone->headers()->add($name, $v);
                }
            } elseif (is_string($value)) {
                $clone->headers()->add($name, $value);
            }
        }

        return $clone;
    }

    public function withAddedHeader($name, $value): static
    {
        $clone = clone $this;

        if (is_array($value)) {
            foreach ($value as $v) {
                if (is_string($v)) {
                    $clone->headers()->add($name, $v);
                }
            }
        } elseif (is_string($value)) {
            $clone->headers()->add($name, $value);
        }

        return $clone;
    }

    public function withoutHeader($name): static
    {
        $clone = clone $this;
        $clone->headers()->remove($name);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        if (null === $this->stream) {
            $this->stream = new Stream();
        }

        return $this->stream;
    }

    public function withBody(
        StreamInterface $body
    ): static {
        if ($body === $this->stream) {
            return $this;
        }

        $clone = clone $this;
        $clone->stream = $body;
        return $clone;
    }

    private function headers(): Headers
    {
        return $this->headers ??= new Headers();
    }
}
