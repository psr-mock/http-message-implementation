<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use Psr\Http\Message\StreamInterface;

final class Stream implements StreamInterface
{
    public function __construct(
        public string $content = ''
    ) {
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function close(): void
    {
    }

    public function detach()
    {
        return null;
    }

    public function getSize(): ?int
    {
        return strlen($this->content);
    }

    public function tell(): int
    {
        return 0;
    }

    public function eof(): bool
    {
        return true;
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): int
    {
        $this->content .= $string;
        return strlen($string);
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        return $this->content;
    }

    public function getContents(): string
    {
        return $this->content;
    }

    public function getMetadata($key = null)
    {
        return null;
    }
}
