<?php

namespace App\Domain\Common\ValueObject;

abstract readonly class AbstractString
{
    public function __construct(
        public string $value
    ) {
    }
}
