<?php

namespace App\Domain\Common\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;

readonly class AbstractDate
{
    private DateTimeImmutable $value;
    private string $format;

    public function __construct(string $value, string $format = DATE_RFC3339_EXTENDED)
    {
        $this->format = $format;
        $this->assertValueIsValid($value);
        $this->value = DateTimeImmutable::createFromFormat($this->format, $value);
    }

    public function getValue(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    public function getValueInMainFormat(): string
    {
        return $this->value->format($this->format);
    }

    private function assertValueIsValid(string $value): void
    {
        $dateTime = DateTimeImmutable::createFromFormat($this->format, $value);
        if (false === $dateTime) {
            throw new InvalidArgumentException(get_class($this) . ' is incorrect!');
        }
    }
}
