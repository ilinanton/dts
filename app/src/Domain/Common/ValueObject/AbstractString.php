<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

abstract readonly class AbstractString
{
    public function __construct(
        public string $value
    ) {
    }

    public function getDbValue(): ?string
    {
        return empty($this->value) ? null : $this->value;
    }
}
