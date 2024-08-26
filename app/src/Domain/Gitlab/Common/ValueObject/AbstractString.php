<?php

namespace App\Domain\Gitlab\Common\ValueObject;

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
