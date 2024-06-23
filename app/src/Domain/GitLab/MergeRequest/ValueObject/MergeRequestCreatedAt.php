<?php

namespace App\Domain\GitLab\MergeRequest\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;

final class MergeRequestCreatedAt
{
    private DateTimeImmutable $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assertValueIsValid(string $value): void
    {
        $dateTime = DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $value);
        if (false === $dateTime) {
            throw new InvalidArgumentException('Created at is incorrect!');
        }
    }
}
