<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use InvalidArgumentException;

abstract readonly class AbstractUnsignedInt
{
    public int $value;

    public function __construct(int $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    private function assertValueIsValid(int $value): void
    {
        if ($value < 0) {
            throw new InvalidArgumentException(get_class($this) . ' is incorrect!');
        }
    }

    public function getDbValue(): ?int
    {
        return $this->value === 0 ? null : $this->value;
    }
}
