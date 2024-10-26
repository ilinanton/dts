<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use InvalidArgumentException;

abstract readonly class AbstractWebUrl
{
    public string $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (false === filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            throw new InvalidArgumentException(get_class($this) . ' is incorrect!');
        }
    }
}
