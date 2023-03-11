<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use Psr\Http\Message\ResponseInterface;

final class Response extends Message implements ResponseInterface
{
    public function __construct(
        public int $statusCode = 200,
        public string $reasonPhrase = ''
    ) {
        parent::__construct();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): self
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase;
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}
