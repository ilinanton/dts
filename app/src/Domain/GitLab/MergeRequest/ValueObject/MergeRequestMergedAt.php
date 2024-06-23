<?php

namespace App\Domain\GitLab\MergeRequest\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;

final class MergeRequestMergedAt
{
    private DateTimeImmutable $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        if (strlen($value) > 0) {
            $this->value = DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $value);
        } else {
            $this->value = new DateTimeImmutable();
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (strlen($value) > 0) {
            $dateTime = DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $value);
            if (false === $dateTime) {
                throw new InvalidArgumentException('Merged at is incorrect!');
            }
        }
    }
}
