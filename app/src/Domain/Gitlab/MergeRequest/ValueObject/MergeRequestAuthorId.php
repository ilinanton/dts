<?php

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

use InvalidArgumentException;

final class MergeRequestAuthorId
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
            throw new InvalidArgumentException('Author id is incorrect!');
        }
    }
}
