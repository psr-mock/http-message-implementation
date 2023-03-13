<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Stringable;

use function fclose;
use function feof;
use function fread;
use function fseek;
use function fstat;
use function ftell;
use function fwrite;
use function is_array;
use function is_resource;
use function is_string;
use function pclose;
use function rewind;
use function stream_get_contents;
use function stream_get_meta_data;

use const SEEK_SET;

final class Stream implements StreamInterface, Stringable
{
    public const FSTAT_MODE_S_IFIFO = 0010000;

    /**
     * @var resource|null
     */
    private $stream;

    /**
     * @var array<mixed>|null
     */
    private ?array $meta = null;

    private ?bool $readable = null;

    private ?bool $writable = null;

    private ?bool $seekable = null;

    private ?int $size = null;

    private ?bool $isPipe = null;

    /**
     * @param  resource|string         $stream A PHP resource handle.
     *
     * @throws InvalidArgumentException If argument is not a resource.
     */
    public function __construct(
        $stream = null
    ) {
        $resource = is_resource($stream) ? $stream : fopen('php://temp', 'wr+');

        // @codeCoverageIgnoreStart
        if (false === $resource) {
            throw new InvalidArgumentException('Invalid resource provided; must be a stream resource');
        }
        // @codeCoverageIgnoreEnd

        $this->attach($resource);

        if (! is_string($stream) && ! is_resource($stream)) {
            $stream = '';
        }

        if (is_string($stream)) {
            $this->write($stream);
            $this->rewind();
        }
    }

    /**
     * @return array<string>|mixed
     */
    public function getMetadata($key = null): mixed
    {
        // @codeCoverageIgnoreStart
        if (! is_resource($this->stream)) {
            return null;
        }
        // @codeCoverageIgnoreEnd

        $this->meta = stream_get_meta_data($this->stream);

        if (null === $key) {
            return $this->meta;
        }

        return array_key_exists($key, $this->meta) ? $this->meta[$key] : null;
    }

    /**
     * Attach new resource to this object.
     *
     * @internal This method is not part of the PSR-7 standard.
     *
     * @param resource $stream A PHP resource handle.
     *
     * @throws InvalidArgumentException If argument is not a valid PHP resource.
     */
    private function attach($stream): void
    {
        // @codeCoverageIgnoreStart
        if (! is_resource($stream)) {
            throw new InvalidArgumentException(__METHOD__ . ' argument must be a valid PHP resource');
        }

        if (is_resource($this->stream)) {
            $this->detach();
        }
        // @codeCoverageIgnoreEnd

        $this->stream = $stream;
    }

    /**
     * @codeCoverageIgnore
     */
    public function detach(): mixed
    {
        $oldResource = $this->stream;

        $this->stream = null;
        $this->meta = null;
        $this->readable = null;
        $this->writable = null;
        $this->seekable = null;
        $this->size = null;
        $this->isPipe = null;

        return $oldResource;
    }


    public function __toString(): string
    {
        // @codeCoverageIgnoreStart
        if (! is_resource($this->stream)) {
            return '';
        }
        // @codeCoverageIgnoreEnd

        if ($this->isSeekable()) {
            $this->rewind();
        }

        return $this->getContents();
    }

    /**
     * @codeCoverageIgnore
     */
    public function close(): void
    {
        if (! is_resource($this->stream)) {
            return;
        }

        if ($this->isPipe()) {
            pclose($this->stream);
        } else {
            fclose($this->stream);
        }

        $this->detach();
    }


    public function getSize(): ?int
    {
        if (is_resource($this->stream) && $this->size === null) {
            $stats = fstat($this->stream);

            if (is_array($stats) && isset($stats['size'])) {
                $this->size = $this->isPipe() ? null : $stats['size'];
            }
        }

        return $this->size;
    }


    public function tell(): int
    {
        $position = false;

        if (is_resource($this->stream)) {
            $position = ftell($this->stream);
        }

        // @codeCoverageIgnoreStart
        if ($position === false || $this->isPipe()) {
            throw new RuntimeException('Could not get the position of the pointer in stream.');
        }
        // @codeCoverageIgnoreEnd

        return $position;
    }


    public function eof(): bool
    {
        return ! is_resource($this->stream) || feof($this->stream);
    }


    public function isReadable(): bool
    {
        // @codeCoverageIgnoreStart
        if ($this->readable !== null) {
            return $this->readable;
        }
        // @codeCoverageIgnoreEnd

        $this->readable = false;

        if (is_resource($this->stream)) {
            $mode = $this->getMetadata('mode');

            if (is_string($mode) && (str_contains($mode, 'r') || str_contains($mode, '+'))) {
                $this->readable = true;
            }
        }

        return $this->readable;
    }


    public function isWritable(): bool
    {
        if ($this->writable === null) {
            $this->writable = false;

            if (is_resource($this->stream)) {
                $mode = $this->getMetadata('mode');

                if (! is_string($mode) || ! str_contains($mode, 'w') && ! str_contains($mode, '+')) {
                    return $this->writable;
                }

                return $this->writable = true;
            }
        }

        // @codeCoverageIgnoreStart
        return $this->writable;
        // @codeCoverageIgnoreEnd
    }


    public function isSeekable(): bool
    {
        if ($this->seekable === null) {
            $this->seekable = false;

            if (is_resource($this->stream)) {
                $this->seekable = ! $this->isPipe() && $this->getMetadata('seekable') !== null;
            }
        }

        return $this->seekable;
    }


    public function seek($offset, $whence = SEEK_SET): void
    {
        if (! is_resource($this->stream) || ! $this->isSeekable() || fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Could not seek in stream.');
        }
    }


    public function rewind(): void
    {
        if (! is_resource($this->stream) || ! $this->isSeekable() || ! rewind($this->stream)) {
            throw new RuntimeException('Could not rewind stream.');
        }
    }


    public function read($length): string
    {
        $data = false;

        if (is_resource($this->stream) && $this->isReadable() && $length >= 0) {
            $data = fread($this->stream, $length);
        }

        if (is_string($data)) {
            return $data;
        }

        throw new RuntimeException('Could not read from stream.');
    }

    public function write($string): int
    {
        $written = false;

        if (is_resource($this->stream) && $this->isWritable()) {
            $written = fwrite($this->stream, $string);
        }

        if ($written !== false) {
            $this->size = null;
            return $written;
        }

        throw new RuntimeException('Could not write to stream.');
    }


    public function getContents(): string
    {
        $contents = false;

        if (is_resource($this->stream)) {
            $contents = stream_get_contents($this->stream);
        }

        if (is_string($contents)) {
            return $contents;
        }

        throw new RuntimeException('Could not get contents of stream.');
    }

    public function isPipe(): bool
    {
        if ($this->isPipe === null) {
            $this->isPipe = false;

            if (is_resource($this->stream)) {
                $stats = fstat($this->stream);

                if (is_array($stats)) {
                    $this->isPipe = ($stats['mode'] & self::FSTAT_MODE_S_IFIFO) !== 0;
                }
            }
        }

        return $this->isPipe;
    }
}
