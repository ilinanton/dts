<?php

namespace App\Domain\GitLab\MergeRequest\ValueObject;

use InvalidArgumentException;

final class MergeRequestTargetBranch
{
    private string $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (0 === strlen($value)) {
            throw new InvalidArgumentException('Target branch is empty!');
        }
    }
}
