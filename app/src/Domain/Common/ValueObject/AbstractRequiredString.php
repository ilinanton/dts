<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use InvalidArgumentException;

abstract readonly class AbstractRequiredString
{
    public string $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (0 === strlen($value)) {
            throw new InvalidArgumentException(get_class($this) . ' is empty!');
        }
    }
}
