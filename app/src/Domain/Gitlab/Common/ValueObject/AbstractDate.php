<?php

namespace App\Domain\Gitlab\Common\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;

readonly class AbstractDate
{
    private DateTimeImmutable $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $value);
    }

    public function getValue(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    private function assertValueIsValid(string $value): void
    {
        $dateTime = DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $value);
        if (false === $dateTime) {
            throw new InvalidArgumentException('Created at is incorrect!');
        }
    }
}
