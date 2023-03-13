<?php

declare(strict_types=1);

namespace PsrMock\Psr7\Collections;

use PsrMock\Psr7\Entities\Header;

final class Headers
{
    private function normalizeHeaderName(string $name): string
    {
        return mb_strtolower(trim($name));
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
     * @return string[][]
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
        }

        return $response;
    }

    /**
     * @param string $name
     *
     * @return string[]
     */
    public function get(string $name): array
    {
        $name     = $this->normalizeHeaderName($name);
        $header   = $this->headers[$name] ?? [];
        $response = [];

        foreach ($header as $value) {
            /** @var Header $value */
            $response[] = $value->getValue();
        }

        return $response;
    }

    /**
     * @param string $name
     *
     * @return Header[][]
     */
    public function getHeader(string $name): array
    {
        $name = $this->normalizeHeaderName($name);
        $response = $this->headers[$name] ?? [];

        /** @var Header[][] $response */

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
