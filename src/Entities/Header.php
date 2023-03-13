<?php

declare(strict_types=1);

namespace PsrMock\Psr7\Entities;

use Stringable;

final class Header implements Stringable
{
    public function __construct(string $name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->name . ': ' . $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
    private string $name;
    private string $value;
}
