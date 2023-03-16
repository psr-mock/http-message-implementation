<?php

declare(strict_types=1);

namespace PsrMock\Psr7\Collections;

use PsrMock\Psr7\Entities\Header;

/**
 * @psalm-api
 */
final class Headers
{
    private function normalizeHeaderName(string $name): string
    {
        return strtolower(trim($name));
    }

    public function add(string $name, string $value): void
    {
        $normalized = $this->normalizeHeaderName($name);
        $this->headers[$normalized] ??= [];
        $this->headers[$normalized][] = new Header($name, $value);
    }

    public function addHeader(Header $header): void
    {
        $normalized = $this->normalizeHeaderName($header->getName());
        $this->headers[$normalized] ??= [];
        $this->headers[$normalized][] = $header;
    }

    /**
     * @return string[]|string[][]
     */
    public function all(): array
    {
        $response = [];

        foreach (array_keys($this->headers) as $header) {
            $normalized = $this->normalizeHeaderName($header);
            $results    = $this->headers[$normalized];

            foreach ($results as $result) {
                /** @var Header $result */
                $response[$result->getName()][] = $result->getValue();
            }

            if (count($response[$result->getName()]) === 1) {
                $response[$result->getName()] = $response[$result->getName()][0];
            }
        }

        return $response;
    }

    /**
     * @param string $name The header name.
     *
     * @return string|string[]
     */
    public function get(string $name): string|array
    {
        $name     = $this->normalizeHeaderName($name);
        $header   = $this->headers[$name] ?? [];
        $response = [];

        foreach ($header as $value) {
            /** @var Header $value */
            $response[] = $value->getValue();
        }

        if (count($response) === 1) {
            $response = $response[0];
        }

        return $response;
    }

    /**
     * @palm-suppress MixedInferredReturnType
     *
     * @param string $name The header name.
     *
     * @return Header|Header[]
     */
    public function getHeader(string $name): Header|array
    {
        $name     = $this->normalizeHeaderName($name);
        $headers  = $this->headers[$name] ?? [];
        $response = [];

        foreach ($headers as $header) {
            if ($header instanceof Header) {
                $response[] = $header;
            }
        }

        if (count($response) === 1) {
            $response = $response[0];
        }

        return $response;
    }

    public function getString(string $name): string
    {
        return implode(', ', $this->get($name));
    }

    public function has(string $name): bool
    {
        $name = $this->normalizeHeaderName($name);

        return isset($this->headers[$name]);
    }

    public function remove(string $name): void
    {
        $name = $this->normalizeHeaderName($name);
        unset($this->headers[$name]);
    }

    public function set(string $name, string $value): void
    {
        $normalized                 = $this->normalizeHeaderName($name);
        $this->headers[$normalized] = [new Header($name, $value)];
    }

    public function setHeader(Header $header): void
    {
        $normalized                 = $this->normalizeHeaderName($header->getName());
        $this->headers[$normalized] = [$header];
    }

    /**
     * @var array<string, array<Header>>
     */
    private array $headers = [];
}
