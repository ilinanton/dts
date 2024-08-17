<?php

namespace App\Domain\GitLab\Common\ValueObject;

use InvalidArgumentException;

abstract readonly class AbstractRequiredUnsignedInt
{
    private int $value;

    public function __construct(int $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    private function assertValueIsValid(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(get_class($this) . ' is incorrect!');
        }
    }
}
