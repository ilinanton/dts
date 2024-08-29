<?php

namespace App\Domain\Common\ValueObject;

abstract readonly class AbstractString
{
    public function __construct(
        private string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
