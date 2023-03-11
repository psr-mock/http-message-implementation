<?php

declare(strict_types=1);

namespace PsrMock\Psr7;

use Psr\Http\Message\UriInterface;

final class Uri implements UriInterface
{
    public function __construct(
        public string $scheme = '',
        public string $host = '',
        public string $path = '',
        public string $query = '',
        public string $fragment = '',
    ) {
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        return $this->host;
    }

    public function getUserInfo(): string
    {
        return '';
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return null;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): self
    {
        $clone = clone $this;
        $clone->scheme = $scheme;
        return $clone;
    }

    public function withUserInfo($user, $password = null): self
    {
        return $this;
    }

    public function withHost($host): self
    {
        $clone = clone $this;
        $clone->host = $host;
        return $clone;
    }

    public function withPort($port): self
    {
        return $this;
    }

    public function withPath($path): self
    {
        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }

    public function withQuery($query): self
    {
        $clone = clone $this;
        $clone->query = $query;
        return $clone;
    }

    public function withFragment($fragment): self
    {
        $clone = clone $this;
        $clone->fragment = $fragment;
        return $clone;
    }

    public function __toString(): string
    {
        $built = '';

        if ($this->scheme !== '') {
            $built .= $this->scheme . '://';
        }

        if ($this->host !== '') {
            $built .= $this->host;
        }

        if ($this->path !== '') {
            $built .= $this->path;
        }

        if ($this->query !== '') {
            $built .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $built .= '#' . $this->fragment;
        }

        return $built;
    }
}
